**PHP and JS Translations**

Made to provide a very lightweight way to perform translations in both php and javascript.

This has been pulled from a larger piece of work that I carried out to be made somewhat standalone so includes some redundancy that has been left in place to illustrate how it could be used as part of a large project.

Examples of its use can be found in translate/translationExamples.php . Its really very simple to use.

----------

**Files**

*translationExamples.php*
Example of using the below

*Translator.php*
Performs the translation

*translations.php*
Contains the associated array (hashmap) or translations. More likely to be called 'english_GB.php' or similar in production with multiple languages.

*translator.js*
The javascript version of Translator.php

*TranslatorTest.php*
Tests for the Translator.php

*Singleton.php*
Extended by Translator.php