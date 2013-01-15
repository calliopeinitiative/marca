<?php

namespace Marca\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Marca\DocBundle\Entity\Markupset;
use Marca\DocBundle\Entity\Markup;

/*
 * Loads some users into the database
 * NOTE: Does NOT promote instructors to FOS User Status "INSTR", do this from the command line
 */

/**
 * Description of LoadMarkupsetData
 *
 * @author Ron
 */
class LoadMarkupsetData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
       
        $user = $manager->merge($this->getReference('instr1-user')); 
        $markupset1 = $this->createMarkupSet('Top20', 1, $user);
        
        $markup1 = $this->createMarkup('Agr PA', $user, 'mc1', 'agr_pa', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'A compound antecedent whose parts are joined by "and" requires a plural pronoun.');
        $markup2 = $this->createMarkup('Agr SV', $user, 'mc2', 'agr_sv', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'In academic varieties of English, verbs must agree with their subjects in number (singular or plural) and in person (first, second, or third).');
        $markup3 = $this->createMarkup('Apos.', $user, 'mc3', 'apos', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Add an apostrophe and -s to form the possessive of most singular nouns, including those that end in -s, and of indefinite pronouns.');
        $markup4 = $this->createMarkup('Capitals', $user, 'mc4', 'caps', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Capitalize the first word of a sentence. If you are quoting a full sentence, capitalize its first word. Capitalization of a sentence following a colon is optional.');
        $markup5 = $this->createMarkup('Comma Coor.', $user, 'mc5', 'comma_coor', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'When a coordinating conjunction (and, but, or, for, nor, so, and yet) is used to connect two independent clauses, a comma should be used before the conjunction.');
        $markup6 = $this->createMarkup('Comma Intro', $user, 'mc6', 'comma_intro', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'A comma usually follows an introductory word, expression, phrase, or clause.');
        $markup7 = $this->createMarkup('Comma Non-Restr', $user, 'mc7', 'comma_non-restr', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Nonrestrictive elements—clauses, phrases, and words that do not limit, or restrict, the meaning of the words they modify—are set off from the rest of the sentence with commas. Restrictive elements do limit meaning and are not set off with commas.');
        $markup8 = $this->createMarkup('Comma Series', $user, 'mc8', 'comma_series', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'A comma is used to separate items in a series.');
        $markup9 = $this->createMarkup('Comma Splice', $user, 'mc9', 'comma_splice', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'A comma splice occurs when a writer links two independent clauses with only a comma.'); 
        $markup10 = $this->createMarkup('Dangling Modifier', $user, 'mc10', 'dangling_mod', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Dangling modifiers are not attached to anything in the sentence.');
        $markup11 = $this->createMarkup('Fragment', $user, 'mc11', 'fragment', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Phrases are groups of words that lack a subject, a verb, or both. When phrases are punctuated like a sentence, they become fragments.');
        $markup12 = $this->createMarkup('Fused Sentence', $user, 'mc12', 'fused', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'A fused sentence results from joining two independent clauses with no punctuation or connecting word between them. The simplest way to revise comma splices or fused sentences is to separate them into two sentences.');
        $markup13 = $this->createMarkup('Hyphens', $user, 'mc13', 'hyphens', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'It is best not to divide words between lines, but when you must do so, break words between syllables. Double-check compound words to be sure they are properly closed up, separated, or hyphenated. If in doubt, consult a dictionary.');
        $markup14 = $this->createMarkup('Missing Word', $user, 'mc14', 'missing_word', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Always check your final draft for errors.');
        $markup15 = $this->createMarkup('Proofreading', $user, 'mc15', 'proof', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Always check your final draft for errors.');
        $markup16 = $this->createMarkup('Pronoun Reference', $user, 'mc16', 'pronoun_ref', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Pronouns should be clearly linked to their appropriate noun.');
        $markup17 = $this->createMarkup('Quotation Integration', $user, 'mc17', 'quote_int', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Decide whether to quote, paraphrase, or summarize. When quoting, introduce quotations with a signal phrase or verb and follow quotations by explaining why you included the selection.');
        $markup18 = $this->createMarkup('Spelling', $user, 'mc18', 'spelling', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Spelling error');
        $markup19 = $this->createMarkup('Tense Shift', $user, 'mc19', 'tense', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'If the verbs in a passage refer to actions occurring at different times, they may require different tenses. Be careful, however, not to change tenses for no reason.');
       $markup20 = $this->createMarkup('Wrong Word', $user, 'mc20', 'wrong_word', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Check your connotations and spelling');
        
      
        
        $manager->persist($markupset1);       
        $manager->persist($markup1);
        $manager->persist($markup2);
        $manager->persist($markup4);
        $manager->persist($markup5);
        $manager->persist($markup6);
        $manager->persist($markup7);
        $manager->persist($markup8);
        $manager->persist($markup9);
        $manager->persist($markup10);
        $manager->persist($markup11);
        $manager->persist($markup12);
        $manager->persist($markup13);
        $manager->persist($markup14);
        $manager->persist($markup15);
        $manager->persist($markup16);
        $manager->persist($markup17);
        $manager->persist($markup18);
        $manager->persist($markup19);
        $manager->persist($markup20);
        

        
       for ($i=2; $i <=25; $i++){
            $num = strval($i);
            $extrauser = $manager->merge($this->getReference('instr'.$num.'-user'));
            $markupset1->addUser($extrauser);
        }
                      
       
        $manager->flush();
        
           
        
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 4;
    }
    
    private function createMarkupSet($name, $shared, $user)
    {
        $markupset = new Markupset();
        $markupset->setName($name);
        $markupset->setShared($shared);
        $markupset->setOwner($user);
        
        return $markupset;
    }
    
    private function createMarkup($name, $user, $color, $value, $markupset, $url=null, $mouseover=null)
    {
        $markup = new Markup();
        $markup->setName($name);
        $markup->setColor($color);
        $markup->setUser($user);
        $markup->setValue($value);
        $markup->setUrl($url);
        $markup->setMouseover($mouseover);
        $markup->addMarkupset($markupset);
        return $markup;
    }
}
