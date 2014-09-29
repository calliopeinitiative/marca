#!/bin/bash

# If called with uga as first argument, enables UGA fixtures
# Otherwise enables non-UGA fixtures
if [ "$1" = 'uga' ]
then
  mv src/Marca/DocBundle/DataFixtures/ORM/Non_UGA_Fixtures/Non_UGA_Markupset.php src/Marca/DocBundle/DataFixtures/ORM/Non_UGA_Fixtures/Non_UGA_Markupset.php.disabled
  mv src/Marca/DocBundle/DataFixtures/ORM/AdditionalFixtures/UGAMarkupSet.php.disabled src/Marca/DocBundle/DataFixtures/ORM/AdditionalFixtures/UGAMarkupSet.php
else
  mv src/Marca/DocBundle/DataFixtures/ORM/Non_UGA_Fixtures/Non_UGA_Markupset.php.disabled src/Marca/DocBundle/DataFixtures/ORM/Non_UGA_Fixtures/Non_UGA_Markupset.php
  mv src/Marca/DocBundle/DataFixtures/ORM/AdditionalFixtures/UGAMarkupSet.php src/Marca/DocBundle/DataFixtures/ORM/AdditionalFixtures/UGAMarkupSet.php.disabled
fi
