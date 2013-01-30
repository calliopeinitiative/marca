<?php

namespace Marca\DocBundle\DataFixtures\ORM\AdditionalFixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAware;
use Marca\DocBundle\Entity\Markupset;
use Marca\DocBundle\Entity\Markup;
use Doctrine\ORM\EntityManager;

/**
 * Description of LoadMarkupsetData
 * To load this fixture  only path, not filename
 * app/console doctrine:fixtures:load --fixtures="pathtofile" --append
 *
 * @author Ron
 */
class LoadMarkupsetData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

$id =1;
$user = $manager->getRepository('MarcaUserBundle:User')->findOneById($id);


$markupset1 = $this->createMarkupSet('Presentation and Design', 1, $user);

$markup1 = $this->createMarkup('Agr PA', $user, 'darkseagreen', 'Agr_PA', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'A compound antecedent whose parts are joined by "and" requires a plural pronoun.','See SMH for more');
$markup2 = $this->createMarkup('Agr SV', $user, 'darkseagreen', 'Sgr_SV', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'In academic varieties of English, verbs must agree with their subjects in number (singular or plural) and in person (first, second, or third).','See SMH for more');
$markup3 = $this->createMarkup('Apos.', $user, 'darkseagreen', 'Apos', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Add an apostrophe and -s to form the possessive of most singular nouns, including those that end in -s, and of indefinite pronouns.','See SMH for more');
$markup4 = $this->createMarkup('Capitals', $user, 'darkseagreen', 'Caps', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Capitalize the first word of a sentence. If you are quoting a full sentence, capitalize its first word. Capitalization of a sentence following a colon is optional.','See SMH for more');
$markup5 = $this->createMarkup('Comma Coor.', $user, 'darkseagreen', 'Comma_Coor', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'When a coordinating conjunction (and, but, or, for, nor, so, and yet) is used to connect two independent clauses, a comma should be used before the conjunction.','See SMH for more');
$markup6 = $this->createMarkup('Comma Intro', $user, 'darkseagreen', 'Comma_Intro', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'A comma usually follows an introductory word, expression, phrase, or clause.','See SMH for more');
$markup7 = $this->createMarkup('Comma Non-Restr', $user, 'darkseagreen', 'comma_non-restr', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Nonrestrictive elements—clauses, phrases, and words that do not limit, or restrict, the meaning of the words they modify—are set off from the rest of the sentence with commas. Restrictive elements do limit meaning and are not set off with commas.','See SMH for more');
$markup8 = $this->createMarkup('Comma Series', $user, 'darkseagreen', 'Comma_Series', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'A comma is used to separate items in a series.','See SMH for more');
$markup9 = $this->createMarkup('Comma Splice', $user, 'darkseagreen', 'Comma_Splice', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'A comma splice occurs when a writer links two independent clauses with only a comma.','See SMH for more'); 
$markup10 = $this->createMarkup('Dangling Modifier', $user, 'darkseagreen', 'Dangling_Modifier', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Dangling modifiers are not attached to anything in the sentence.','See SMH for more');
$markup11 = $this->createMarkup('Fragment', $user, 'darkseagreen', 'fragment', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Phrases are groups of words that lack a subject, a verb, or both. When phrases are punctuated like a sentence, they become fragments.','See SMH for more');
$markup12 = $this->createMarkup('Fused Sentence', $user, 'darkseagreen', 'Fused_Sentence', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'A fused sentence results from joining two independent clauses with no punctuation or connecting word between them. The simplest way to revise comma splices or fused sentences is to separate them into two sentences.','See SMH for more');
$markup13 = $this->createMarkup('Hyphens', $user, 'darkseagreen', 'Hyphens', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'It is best not to divide words between lines, but when you must do so, break words between syllables. Double-check compound words to be sure they are properly closed up, separated, or hyphenated. If in doubt, consult a dictionary.','See SMH for more');
$markup14 = $this->createMarkup('Missing Word', $user, 'darkseagreen', 'Missing_Word', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Always check your final draft for errors.','See SMH for more');
$markup15 = $this->createMarkup('Proofreading', $user, 'darkseagreen', 'proof', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Always check your final draft for errors.','See SMH for more');
$markup16 = $this->createMarkup('Pronoun Reference', $user, 'darkseagreen', 'pronoun_ref', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Pronouns should be clearly linked to their appropriate noun.','See SMH for more');
$markup17 = $this->createMarkup('Abbreviations', $user, 'darkseagreen', 'abbreviations', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Abbreviation errors detract from your ethos as a writer.','See SMH for more');
$markup18 = $this->createMarkup('Spelling', $user, 'darkseagreen', 'spelling', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Spelling error','See SMH for more');
$markup19 = $this->createMarkup('Tense Shift', $user, 'darkseagreen', 'tense', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'If the verbs in a passage refer to actions occurring at different times, they may require different tenses. Be careful, however, not to change tenses for no reason.','See SMH for more');
$markup20 = $this->createMarkup('Wrong Word', $user, 'darkseagreen', 'wrong_word', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Check your connotations and spelling','See SMH for more');
$markup21 = $this->createMarkup('Comma Unnecessary', $user, 'darkseagreen', 'comma_unnec', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'A comma is not required at this point.','See SMH for more');
$markup22 = $this->createMarkup('Dangling Modifier', $user, 'darkseagreen', 'dang_mod', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Dangling modifiers are not attached to anything in the sentence.','See SMH for more');
$markup23 = $this->createMarkup('Dash', $user, 'darkseagreen', 'dash', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Dashes are used to insert information into a sentence.','See SMH for more');
$markup24 = $this->createMarkup('Documentation Mechanics', $user, 'darkseagreen', 'doc_mechanics', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'A critical component of accurate documentation is the proper use of punctuation signals.','See SMH for more');
$markup25 = $this->createMarkup('Ellipses', $user, 'darkseagreen', 'ellipses', $markupset1, 'Ellipses are used to note that information has been removed from a quote or that there is a pause.','See SMH for more');
$markup26 = $this->createMarkup('Italics', $user, 'darkseagreen', 'italics', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'In general, italics are used for the titles of long or complete works; use quotation marks for shorter works or sections of works.','See SMH for more');
$markup27 = $this->createMarkup('Misplaced Modifier', $user, 'darkseagreen', 'misplace_mod', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Misplaced modifiers are words, phrases, and clauses that cause ambiguity or confusion because they are not close enough to the words they modify or because they seem to point both to words before and to words after them.','See SMH for more');
$markup29 = $this->createMarkup('Numbers', $user, 'darkseagreen', 'numbers', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Depending on their use and length, numbers can be either written numerically or spelled out.','See SMH for more');
$markup30 = $this->createMarkup('Parallel Structure', $user, 'darkseagreen', 'par_structure', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'All items in a series should be in parallel form.','See SMH for more');
$markup31 = $this->createMarkup('Punctuation Error', $user, 'darkseagreen', 'punctuation', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'See Part 9 of SMH, "Punctuation."','See SMH for more');
$markup32 = $this->createMarkup('Quotation Marks', $user, 'darkseagreen', 'quote_marks', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'In American English, double quotation marks signal a direct quotation. Periods and commas should be inside the quotation marks.','See SMH for more');
$markup33 = $this->createMarkup('Source Acknowledgment Wrong', $user, 'darkseagreen', 'source_wrong', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'In your writing, full and accurate documentation is important because it helps build your credibility as a writer and researcher by giving credit to those people whose works influenced your ideas.','See SMH for more');
$markup34 = $this->createMarkup('Source Acknowledgment Missing', $user, 'darkseagreen', 'source_missing', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'In your writing, full and accurate documentation is important because it helps build your credibility as a writer and researcher by giving credit to those people whose works influenced your ideas.','See SMH for more');
$markup35 = $this->createMarkup('Source Acknowledgment Incomplete', $user, 'darkseagreen', 'source_incomplete', $markupset1, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'In your writing, full and accurate documentation is important because it helps build your credibility as a writer and researcher by giving credit to those people whose works influenced your ideas.','See SMH for more');


$markupset2 = $this->createMarkupSet('Coherence', 1, $user);

$markup41= $this->createMarkup('Confusing sentence', $user, 'cadetblue', 'confusing_sentence', $markupset2, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Effective sentences have two main characteristics: They emphasize your ideas clearly, and they do so as concisely as possible. See Part 7 of the SMH.','See SMH for more');

$markup42= $this->createMarkup('Dropped quote', $user, 'cadetblue', 'dropped_quote', $markupset2, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Introduce quotations with a signal phrase or verb and follow quotations by explaining why you included the selection.','See SMH for more');

$markup43= $this->createMarkup('Needs transitional idea', $user, 'cadetblue', 'needs_transitional_idea', $markupset2, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Transitional words and phrases signal relationships between and among paragraphs, and bring coherence by helping readers follow the progression of one idea to the next.','See SMH for more');

$markup44= $this->createMarkup('Needs parallel structure', $user, 'cadetblue', 'needs_parallel_structure', $markupset2, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'All items in a series should be parallel in form.','See SMH for more');

$markup45= $this->createMarkup('Quote integration', $user, 'cadetblue', 'quote_integration', $markupset2, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Decide whether to quote, paraphrase, or summarize. When quoting, introduce quotations with a signal phrase or verb and follow quotations by explaining why you included the selection.','See SMH for more');

$markup46= $this->createMarkup('Repetition ineffective', $user, 'cadetblue', 'repetition_ineffective', $markupset2, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Be careful to use repetition only for a deliberate purpose.','See SMH for more');

$markup47= $this->createMarkup('Syntax ineffective', $user, 'cadetblue', 'syntax_ineffective', $markupset2, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'The arrangement of words in a sentence should reveal the relationship of each to the whole sentence and to one another.','See SMH for more');

$markup48= $this->createMarkup('Transition ineffective', $user, 'cadetblue', 'transition_ineffective', $markupset2, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Transitional words and phrases signal relationships between and among paragraphs, and bring coherence by helping readers follow the progression of one idea to the next.','See SMH for more');

$markup49= $this->createMarkup('Vary sentence structures', $user, 'cadetblue', 'vary_sentence_structure', $markupset2, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Although both series of short and of long sentences can be effective in individual situations, frequent alternation in sentence length characterizes much memorable writing. After one or more long sentences that express complex ideas or images, the pith of a short sentence can be refreshing and arresting.','See SMH for more');



$markupset3 = $this->createMarkupSet('Evidence', 1, $user);

$markup51 = $this->createMarkup('Ineffective example or detail', $user, 'chartreuse', 'ineffective_example_or_detail', $markupset3, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'The evidence that you use should strongly support your thesis.','See SMH for more');

$markup52 = $this->createMarkup('Needs more support', $user, 'chartreuse', 'needs_more_support', $markupset3, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'The more evidence you have to support an idea, the stronger that idea will appear to your reader.','See SMH for more');

$markup53 = $this->createMarkup('Support not relevant to claim', $user, 'chartreuse', 'support_not_relevant_to_claim', $markupset3, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Your evidence should be directly linked to your argument.','See SMH for more');

$markup54 = $this->createMarkup('Unsupported claim or opinion', $user, 'chartreuse', 'unsupported_claim_or_opinion', $markupset3, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'One of the most common writing errors is the lack of supporting evidence. You should always have good support for your ideas.','See SMH for more');
    

$markupset4 = $this->createMarkupSet('Unity', 1, $user);

$markup61 = $this->createMarkup('Missing Thesis', $user, 'darkorange', 'missing_thesis', $markupset4, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'A thesis states the main idea of a piece of writing.','See SMH for more');

$markup62 = $this->createMarkup('Missing Topic Sentence', $user, 'darkorange', 'missing_topic_sentence', $markupset4, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Individual paragraphs should be unified around a single idea, which is stated in the topic sentence.','See SMH for more');

$markup63 = $this->createMarkup('Sentence not related to topic', $user, 'darkorange', 'sentence_not_related_to_topic', $markupset4, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Each sentence in a paragraph should be connected to the topic sentence.','See SMH for more');

$markup64 = $this->createMarkup('Thesis lacks comment', $user, 'darkorange', 'thesis_lacks_comment', $markupset4, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'A thesis statement should say something about, or comment on, the topic.','See SMH for more');

$markup65 = $this->createMarkup('Thesis lacks topic', $user, 'darkorange', 'thesis_lacks_topic', $markupset4, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'A thesis should have a topic, i.e., it should be about something.','See SMH for more');

$markup66 = $this->createMarkup('Thesis needs work', $user, 'darkorange', 'thesis_needs_work', $markupset4, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Every main argument in the paper should be connected to the thesis statement.','See SMH for more');

$markup67 = $this->createMarkup('Topic not related to thesis', $user, 'darkorange', 'topic_not_related_to_thesis', $markupset4, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Every main argument in the paper should be connected to the thesis statement.','See SMH for more');

$markup68 = $this->createMarkup('Topic sentence needs work', $user, 'darkorange', 'topic_sentence_needs_work', $markupset4, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'A topic sentence should clearly articulate the main idea of the paragraph.','See SMH for more');

$markup69 = $this->createMarkup('Sentence not related to thesis', $user, 'darkorange', 'sentence_not_related_to_thesis', $markupset4, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Each sentence in an essay should be developing the main idea.','See SMH for more');



$markupset5 = $this->createMarkupSet('Audience Awareness', 1, $user);

$markup71= $this->createMarkup('Detail inappropriate', $user, 'coral', 'detail_innapropriate', $markupset5, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Some details are inappropriate for a particular or a general audience.','See SMH for more');

$markup72= $this->createMarkup('Diction awkward', $user, 'coral', 'diction_awkward', $markupset5, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Work for clear and readable prose by thinking about purpose, topic, and audience.','See SMH for more');

$markup73= $this->createMarkup('Diction biased', $user, 'coral', 'diction_biased', $markupset5, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Watch out for stereotypes and unintended assumptions.','See SMH for more');

$markup74= $this->createMarkup('Diction ineffective', $user, 'coral', 'diction_ineffective', $markupset5, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Positive and negative connotations can shift meaning significantly.','See SMH for more');

$markup75= $this->createMarkup('Diction too formal', $user, 'coral', 'diction_too_formal', $markupset5, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Pompous language, euphemisms, and doublespeak can produce unintended effects.','See SMH for more');

$markup76= $this->createMarkup('Diction too technical', $user, 'coral', 'diction_too_technical', $markupset5, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Jargon and neologisms should be reserved for a specific technical audience.','See SMH for more');

$markup77= $this->createMarkup('Diction too informal', $user, 'coral', 'diction_too_informal', $markupset5, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Slang and colloquial language can be misunderstood.','See SMH for more');

$markup78= $this->createMarkup('Evidence overly biased', $user, 'coral', 'evidence_overly_biased', $markupset5, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Some evidence may insult or annoy your audience.','See SMH for more');

$markup79= $this->createMarkup('Expletive construction ineffective', $user, 'coral', 'expletive_construction_ineffective', $markupset5, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Constructions that introduce a sentence with there or it plus a form of the verb to be. Choose strong verbs.','See SMH for more');

$markup80= $this->createMarkup('Linking verb ineffective', $user, 'coral', 'linking_verb_ineffective', $markupset5, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'A form or close cousin of the verb to be. Choose strong verbs.','See SMH for more');

$markup81= $this->createMarkup('Need less explanation', $user, 'coral', 'need_less_explanation', $markupset5, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Too much explanation can bore your audience.','See SMH for more');

$markup82= $this->createMarkup('Be concise', $user, 'coral', 'be_concise', $markupset5, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Usually you\'ll want to be concise—to make your point in the fewest possible words.','See SMH for more');

$markup83= $this->createMarkup('Needs more explanation', $user, 'coral', 'needs_more_explanation', $markupset5, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Too little explanation can confuse your audience.','See SMH for more');

$markup84= $this->createMarkup('Passive voice ineffective', $user, 'coral', 'passive_voice_ineffective', $markupset5, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Choose strong subjects and verbs to make your prose clear and understandable.','See SMH for more');

$markup85= $this->createMarkup('Use possessive', $user, 'coral', 'use_possessive', $markupset5, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'The possessive case denotes ownership or possession of one thing by another. Apostrophes are used in the possessive case of nouns and some pronouns.','See SMH for more');

$markupset6 = $this->createMarkupSet('Top20', 1, $user);

$markup86 = $this->createMarkup('Agr PA', $user, 'darkseagreen', 'agr_pa', $markupset6, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'A compound antecedent whose parts are joined by "and" requires a plural pronoun.','See SMH for more');
$markup87 = $this->createMarkup('Agr SV', $user, 'darkseagreen', 'agr_sv', $markupset6, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'In academic varieties of English, verbs must agree with their subjects in number (singular or plural) and in person (first, second, or third).','See SMH for more');
$markup88 = $this->createMarkup('Apos.', $user, 'darkseagreen', 'apos', $markupset6, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Add an apostrophe and -s to form the possessive of most singular nouns, including those that end in -s, and of indefinite pronouns.','See SMH for more');
$markup89 = $this->createMarkup('Capitals', $user, 'darkseagreen', 'caps', $markupset6, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Capitalize the first word of a sentence. If you are quoting a full sentence, capitalize its first word. Capitalization of a sentence following a colon is optional.','See SMH for more');
$markup90 = $this->createMarkup('Comma Coor.', $user, 'darkseagreen', 'comma_coor', $markupset6, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'When a coordinating conjunction (and, but, or, for, nor, so, and yet) is used to connect two independent clauses, a comma should be used before the conjunction.','See SMH for more');
$markup91 = $this->createMarkup('Comma Intro', $user, 'darkseagreen', 'comma_intro', $markupset6, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'A comma usually follows an introductory word, expression, phrase, or clause.','See SMH for more');
$markup92 = $this->createMarkup('Comma Non-Restr', $user, 'darkseagreen', 'comma_non-restr', $markupset6, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Nonrestrictive elements—clauses, phrases, and words that do not limit, or restrict, the meaning of the words they modify—are set off from the rest of the sentence with commas. Restrictive elements do limit meaning and are not set off with commas.','See SMH for more');
$markup93 = $this->createMarkup('Comma Series', $user, 'darkseagreen', 'comma_series', $markupset6, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'A comma is used to separate items in a series.','See SMH for more');
$markup94 = $this->createMarkup('Comma Splice', $user, 'darkseagreen', 'comma_splice', $markupset6, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'A comma splice occurs when a writer links two independent clauses with only a comma.','See SMH for more'); 
$markup95 = $this->createMarkup('Dangling Modifier', $user, 'darkseagreen', 'dangling_mod', $markupset6, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Dangling modifiers are not attached to anything in the sentence.','See SMH for more');
$markup96 = $this->createMarkup('Fragment', $user, 'darkseagreen', 'fragment', $markupset6, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Phrases are groups of words that lack a subject, a verb, or both. When phrases are punctuated like a sentence, they become fragments.','See SMH for more');
$markup97 = $this->createMarkup('Fused Sentence', $user, 'darkseagreen', 'fused', $markupset6, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'A fused sentence results from joining two independent clauses with no punctuation or connecting word between them. The simplest way to revise comma splices or fused sentences is to separate them into two sentences.','See SMH for more');
$markup98 = $this->createMarkup('Hyphens', $user, 'darkseagreen', 'hyphens', $markupset6, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'It is best not to divide words between lines, but when you must do so, break words between syllables. Double-check compound words to be sure they are properly closed up, separated, or hyphenated. If in doubt, consult a dictionary.','See SMH for more');
$markup99 = $this->createMarkup('Missing Word', $user, 'darkseagreen', 'missing_word', $markupset6, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Always check your final draft for errors.','See SMH for more');
$markup100 = $this->createMarkup('Proofreading', $user, 'darkseagreen', 'proof', $markupset6, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Always check your final draft for errors.','See SMH for more');
$markup101 = $this->createMarkup('Pronoun Reference', $user, 'darkseagreen', 'pronoun_ref', $markupset6, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Pronouns should be clearly linked to their appropriate noun.','See SMH for more');
$markup102 = $this->createMarkup('Quotation Integration', $user, 'cadetblue', 'quote_int', $markupset6, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Decide whether to quote, paraphrase, or summarize. When quoting, introduce quotations with a signal phrase or verb and follow quotations by explaining why you included the selection.','See SMH for more');
$markup103 = $this->createMarkup('Spelling', $user, 'darkseagreen', 'spelling', $markupset6, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Spelling error','See SMH for more');
$markup104 = $this->createMarkup('Tense Shift', $user, 'darkseagreen', 'tense', $markupset6, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'If the verbs in a passage refer to actions occurring at different times, they may require different tenses. Be careful, however, not to change tenses for no reason.','See SMH for more');
       $markup105 = $this->createMarkup('Wrong Word', $user, 'darkseagreen', 'wrong_word', $markupset6, 'http://ebooks.bfwpub.com/smhandbook7e.php', 'Check your connotations and spelling','See SMH for more');
       
 $manager->persist($markupset1);       
 $manager->persist($markupset2);     
 $manager->persist($markupset3);     
 $manager->persist($markupset4);     
 $manager->persist($markupset5);     
 $manager->persist($markupset6); 
 
$manager->persist($markup1);
$manager->persist($markup2);
$manager->persist($markup3);
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
$manager->persist($markup17);
$manager->persist($markup16);
$manager->persist($markup18);
$manager->persist($markup19);
$manager->persist($markup20);

$manager->persist($markup21);
$manager->persist($markup22);
$manager->persist($markup23);
$manager->persist($markup24);
$manager->persist($markup25);
$manager->persist($markup26);
$manager->persist($markup27);
$manager->persist($markup29);


$manager->persist($markup30);
$manager->persist($markup31);
$manager->persist($markup32);
$manager->persist($markup33);
$manager->persist($markup34);
$manager->persist($markup35);

$manager->persist($markup41);
$manager->persist($markup42);
$manager->persist($markup43);
$manager->persist($markup44);
$manager->persist($markup45);
$manager->persist($markup46);
$manager->persist($markup47);
$manager->persist($markup48);
$manager->persist($markup49);


$manager->persist($markup51);
$manager->persist($markup52);
$manager->persist($markup53);
$manager->persist($markup54);
$manager->persist($markup61);
$manager->persist($markup62);
$manager->persist($markup63);
$manager->persist($markup64);
$manager->persist($markup65);
$manager->persist($markup66);
$manager->persist($markup67);
$manager->persist($markup68);
$manager->persist($markup69);

$manager->persist($markup71);
$manager->persist($markup72);
$manager->persist($markup73);
$manager->persist($markup74);
$manager->persist($markup75);
$manager->persist($markup76);
$manager->persist($markup77);
$manager->persist($markup78);
$manager->persist($markup79);
$manager->persist($markup80);
$manager->persist($markup81);
$manager->persist($markup82);
$manager->persist($markup83);
$manager->persist($markup84);
$manager->persist($markup85);
$manager->persist($markup86);
$manager->persist($markup87);
$manager->persist($markup88);
$manager->persist($markup89);
$manager->persist($markup90);
$manager->persist($markup91);
$manager->persist($markup92);
$manager->persist($markup93);
$manager->persist($markup94);
$manager->persist($markup95);
$manager->persist($markup96);
$manager->persist($markup97);
$manager->persist($markup98);
$manager->persist($markup99);


$manager->persist($markup100);
$manager->persist($markup101);
$manager->persist($markup102);
$manager->persist($markup103);
$manager->persist($markup104);
$manager->persist($markup105);
       
$manager->flush();
    
    }
    

    
    private function createMarkupSet($name, $shared, $user)
    {
$markupset = new Markupset();
$markupset->setName($name);
$markupset->setShared($shared);
$markupset->setOwner($user);

return $markupset;
    }
    
    private function createMarkup($name, $user, $color, $value, $markupset, $url=null, $mouseover=null, $linktext=null)
    {
$markup = new Markup();
$markup->setName($name);
$markup->setColor($color);
$markup->setUser($user);
$markup->setValue($value);
$markup->setUrl($url);
$markup->setMouseover($mouseover);
$markup->addMarkupset($markupset);
$markup->setLinktext($linktext);
return $markup;
    }
}


