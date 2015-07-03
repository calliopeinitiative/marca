<?php

namespace Marca\AssignmentBundle\Controller;

use Marca\HomeBundle\Controller\Controller;
use Marca\AssignmentBundle\Entity\Assignment;
use Marca\AssignmentBundle\Entity\AssignmentStage;
use Marca\AssignmentBundle\Entity\AssignmentSubmission;
use Marca\AssignmentBundle\Form\AssignmentStageType;
use Marca\AssignmentBundle\Form\AssignmentType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AssignmentController
 * @package Marca\AssignmentBundle\Controller
 * @Route("/assignment")
 */
class AssignmentController extends Controller
{

    /**
     * Create Sidebar fragment
     *
     * @Route("/{courseid}/sidebar", name="assignments_sidebar")
     */
    public function createSidebarAction($courseid)
    {
        $em = $this->getEm();
        $user = $this->getUser();
        $course = $this->getCourse();
        //fetch the user's entry in the current course roll, since that is where we store his/her current assignment for this course
        $user_roll = $em->getRepository('MarcaCourseBundle:Roll')->findUserInCourse($course, $user);
        $currentAssignment = $user_roll->getCurrentAssignment();
        //fetch the rest of assignments from the course
        //this seems cleaner than some twig magic to fetch from current assignment, but dunno
        $assignments = $course->getAssignments();
        return $this->render('MarcaAssignmentBundle::sidebar.html.twig', array(
            'current_assignment' => $currentAssignment,
            'assignments' => $assignments
        ));
    }

    /**
     * Index of Assignments
     * @Route("/{courseid}/index", name="assignment_index")
     */
    public function indexAction(){
        $course = $this->getCourse();
        $assignments = $course->getAssignments();
        $role = $this->getCourseRole();
        return $this->render('MarcaAssignmentBundle:Assignment:index.html.twig', array(
        'assignments' => $assignments,
         'role' => $role
        ));
    }

    /**
     * Show an assignment for review or student work
     *
     * @Route("/{courseid}/{id}/show", name="assignment_show")
     */
    public function showAction($id){
        $em = $this->getEm();
        $assignment = $em->getRepository('MarcaAssignmentBundle:Assignment')->find($id);
        //fetch the user roll and store the assignment there as current assignment for later use
        $user = $this->getUser();
        $course = $this->getCourse();
        $user_roll = $em->getRepository('MarcaCourseBundle:Roll')->findUserInCourse($course, $user);
        $user_roll->setCurrentAssignment($assignment);
        //grab the role from the roll, since we have it anyway
        $role = $user_roll->getRole();
        $em->persist($user_roll);
        $em->flush();

        //fetch the user's submissions for this assignment (using submission repository method)
        $submissions = $em->getRepository('MarcaAssignmentBundle:AssignmentSubmission')->findSubmissionsForUser($user, $assignment);
        //step through the submissions and create an array of submissions with the stage.id of the submission as the key value
        $submissionsByStage = array ();
        foreach($submissions as $submission){
           $submissionsByStage[$submission->getStage()->getId()] = $submission;
        }
        return $this->render('MarcaAssignmentBundle:Assignment:show.html.twig', array(
            'submissions' => $submissionsByStage,
            'assignment' => $assignment,
            'role' => $role
        ));
    }

    /**
     * Post processing when a student saves progress on an assignment submission without saving
     *
     * @Route("/{courseid}/{id}/submissionsave", name="submission_save")
     */
    public function submissionSaveAction($courseid, $id){
        $em = $this->getEm();
        $submission = $em->getRepository('MarcaAssignmentBundle:AssignmentSubmission')->find($id);
        $assignmentId = $submission->getStage()->getAssignment()->getId();
        return $this->redirect($this->generateUrl('assignment_show', array('courseid'=>$courseid, 'id' => $assignmentId)));
    }

    /**
     * Post processing when a student submits an assignment
     *
     * @Route("/{courseid}/{id}/submit", name="assignment_submit")
     */
    public function submitAssignmentAction($courseid, $id){
        $em = $this->getEm();
        $submission = $em->getRepository('MarcaAssignmentBundle:AssignmentSubmission')->find($id);
        $assignmentId = $submission->getStage()->getAssignment()->getId();
        $submission->setSubmitted(new \DateTime("now"));

        $etherpadInstance = $this->get('etherpadlite');
        $etherpaddoc = $submission->getFile()->getEtherpaddoc();
        $revisionId = $etherpadInstance->getRevisionsCount($etherpaddoc)->revisions;
        $etherpadInstance->saveRevision($etherpaddoc, $revisionId);
        $submission->setEtherpadRev($revisionId);

        $em->persist($submission);
        $em->flush();
        return $this->redirect($this->generateUrl('assignment_show', array('courseid'=>$courseid, 'id' => $assignmentId)));
    }

    /**
     * @Route("/{courseid}/new", name="assignment_new")
     * @Template()
     */
    public function newAction(Request $request)
    {
        //NOTE: This controller uses the single-function method for rendering and submitting forms, as described in the Symfony cookbook for Symfony 2.3 and above
        $course = $this->getCourse();
        $user = $this->getUser();
        $name = $course->getName();
        $em = $this->getEm();

        $assignment = new Assignment();
        $assignment->setCourse($course);
        $assignment->setUser($user);
        $assignmentStage1 = new AssignmentStage();
        $assignmentStage1->setName('Pre-write');
        $assignment->addStage($assignmentStage1);
        $assignmentStage2 = new AssignmentStage();
        $assignmentStage2->setName('Draft 1');
        $assignment->addStage($assignmentStage2);
        $form = $this->createForm(new AssignmentType(), $assignment, array('course'=>$course));
        $form->handleRequest($request);

        if($form->isValid()){
            //Set the "next" and "previous" of each stage by stepping through the stage array and setting next to each stage's key + 1 and previous to each stage's key - 1
            $stages = $assignment->getStages();
            foreach($stages as $key=>$stage){
                if($key > 0){
                    $stage->setPrevious($stages[$key - 1]);
                }
                if($key < count($stages)){
                    $stage->setNext($stages[$key + 1]);
                }
            }
            $em->persist($assignment);
            $em->flush();
            return $this->redirect($this->generateUrl('assignment_index', array('courseid' => $course->getId())));
        }

        return $this->render('MarcaAssignmentBundle:Assignment:new.html.twig', array(
            'form' => $form->createView())
            );
    }

    /**
     * Display an assignment for instructor review
     * @Route("/{courseid}/{id}/edit", name="assignment_edit")
     */
    public function editAction(Request $request, $id)
    {

        $em = $this->getEm();
        $course = $this->getCourse();
        $assignment = $em->getRepository('MarcaAssignmentBundle:Assignment')->find($id);
        $form = $this->createForm(new AssignmentType(), $assignment);
        $form->handleRequest($request);

        if($form->isValid()){
            $em->persist($assignment);
            $em->flush();
            return $this->redirect($this->generateUrl('assignment_index', array('courseid' => $course->getId())));
        }

        return $this->render('MarcaAssignmentBundle:Assignment:new.html.twig', array(
                'form' => $form->createView(),
                'id' => $id, )
        );
    }

    /**
     * Continues work from a previous stage
     * @Route("/{courseid}/{stageid}/{submissionid}/continue", name="submission_continue")
     */
    public function continueSubmissionAction($stageid, $submissionid, $courseid){
        $em = $this->getEm();
        $user = $this->getUser();

        $stage = $em->getRepository('MarcaAssignmentBundle:AssignmentStage')->find($stageid);
        $previousSubmission = $em->getRepository('MarcaAssignmentBundle:AssignmentSubmission')->find($submissionid);
        $previousFile = $previousSubmission->getFile();

        $submission = new AssignmentSubmission();
        $submission->setUser($user);
        $submission->setStage($stage);
        $submission->setFile($previousFile);
        $submission->setCreated(new \DateTime('now'));

        $em->persist($submission);
        $em->flush();

        return $this->redirect($this->generateUrl('doc_edit', array('courseid'=>$courseid, 'id'=>$submission->getFile()->getId(), 'submissionid'=>$submission->getId(), 'view'=>'app')));

    }

    /**
     * Delete an assignment
     * @Route("/{courseid}/{id}/delete", name="assignment_delete")
     */
    public function deleteAction(Request $request, $id, $courseid)
    {
        //We delete the assignment by manually nulling the course association
        //This makes assignments capable of being found and undeleted, but it will also leave them hanging around in the db
        //TODO Function to clean up "deleted" assignments
        $em = $this->getEm();
        $assignment = $em->getRepository('MarcaAssignmentBundle:Assignment')->find($id);
        $assignment->setCourse(null);
        $em->persist($assignment);
        $em->flush();
        return $this->redirect($this->generateUrl('assignment_index', array('courseid'=>$courseid)));
    }

}
