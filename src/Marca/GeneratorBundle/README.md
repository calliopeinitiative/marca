
Marca Generator Bundle
========================

Function of the Bundle
------------------------

The Generator Bundle is an override of the Symfony bundle crud generator to meet our needs.


Bundle Creation Cheat Sheet

Create the Bundle:
app/console generate:bundle
(in the dialog, use defaults except enter "yes" for generating the whole directory structure)
 
Create the entity or entites for the bundle
app/console doctrine:generate:entity
(when asked answer "yes" for empty repository)
 
Generate getters and setters (if adding by hand, eg timestampable)
app/console doctrine:generate:entities Entityname --no-backup
 
 
Add to DB
app/console doctrine:schema:update --dump-sql
app/console doctrine:schema:update --force
 
Create the CRUD
Note: this is the actual bit that overrides the default crud generator
app/console mydoctrine:generate:crud
("yes" for write actions

Update the Bundle name at the top of each crud template
Add Resources/views/layout.html.twig for the bundle toolbar  (copy from some other bundle)

