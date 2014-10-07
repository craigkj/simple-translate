<html>
<head>

    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../css/custom.css">

    <?php
    include 'Translator.php';
    // Get and instance of the Translator
    $translator = Translator::getInstance();
    ?>

    <script src="translator.js"></script>
    <script type="text/javascript">

        /**
         * Initialises the translator.
         *
         * @param translations - json encoded translations
         */
        var initTranslator = function (translations) {
            translator = new JSTranslator();
            translator.setup(translations);
        };

    </script>

</head>
<body>

<h1>PHP and JS Translation examples</h1>

<p>Lightweight translations in both php and js. Supports variables and pluralisation</p>
<p>View the source of this page to see how everything is wired up and a few examples, here only a single translation
file is provided and used, but in practice this file would alter based on language/locale etc.</p>

<div>
    <h2>Translate via PHP (Translator.php)</h2>

    <div>
        <h3>Simple Translations:</h3>
        <dl>
            <dt>Explanation:</dt>
            <dd>Translation for term: 'translate_this' :</dd>
            <dt>Output:</dt>
            <dd><?php echo($translator->translate('translate_this')); ?></dd>
            <dt>Code:</dt>
            <dd class="code">$translator->translate('translate_this');</dd>
        </dl>
        <dl>
            <dt>Explanation:</dt>
            <dd>Translation for translation term:  'name_of_the_author':</dd>
            <dt>Output:</dt>
            <dd><?php echo($translator->translate('name_of_the_author')); ?></dd>
            <dt>Code:</dt>
            <dd class="code">$translator->translate('name_of_the_author')</dd>
        </dl>
        <dl>
            <dt>Explanation:</dt>
            <dd>'Translation for translation term:  'error_has_occurred':'</dd>
            <dt>Output:</dt>
            <dd><?php echo($translator->translate('error_has_occurred')); ?></dd>
            <dt>Code:</dt>
            <dd class="code">$translator->translate('error_has_occurred')</dd>
        </dl>
    </div>

    <h3>Translations with pluralisation</h3>
    <dl>
        <dt>Explanation:</dt>
        <dd>Translation for 'x_apples' when we have 0 apples:</dd>
        <dt>Output</dt>
        <dd>
            <?php echo($translator->translate('x_apples', array(), 0)); ?>
        </dd>
        <dt>Code</dt>
        <dd class="code">$translator->translate('x_apples', array(), 0)); </dd>
    </dl>

    <dl>
        <dt>Explanation:</dt>
        <dd>Translation for 'x_apples' when we have 1 apple:</dd>
        <dt>Output</dt>
        <dd>
            <?php echo($translator->translate('x_apples', array(), 1)); ?>
        </dd>
        <dt>Code</dt>
        <dd class="code">$translator->translate('x_apples', array(), 1)); </dd>
    </dl>

    <dl>
        <dt>Explanation:</dt>
        <dd>Translation for 'x_apples' when we have 5 apples:</dd>
        <dt>Output</dt>
        <dd><?php echo($translator->translate('x_apples', array(), 5)); ?>
        </dd>
        <dt>Code</dt>
        <dd class="code">$translator->translate('x_apples', array(), 5); </dd>
    </dl>

    <h3>Translations with variable use</h3>

    <?php
    $translationValues = array('terms' => 'Craigkj',
        'desc' => '"authors"',
        'total' => 'a few');
    ?>

    <div class="notes">
        <p>First, we need to set up the variables:</p>
        <p class="code">
            $translationValues = array('terms' => 'Craigkj',
            'desc' => '"authors"',
            'total' => 'a few');
        </p>
    </div>

    <dl>
        <dt>Explanation:</dt>
        <dd>Translation with variables passed in, in this case three (terms, desc and total)</dd>
        <dt>Output</dt>
        <dd>
            <?php echo ($translator->translate('search_for_x_in_x_showing_x_results',
                    $translationValues, 0)) . '<br />'; ?>
        </dd>
        <dt>Code</dt>
        <dd class="code">$translator->translate('search_for_x_in_x_showing_x_results', $translationValues, 0)</dd>
    </dl>

</div>

<div>
    <h2>Translate via JS (translation.js)</h2>

    <p>... best check the console for these...</p>
    <script type="text/javascript">

        translations = <?php echo $translator->getJsonEncodedTranslations(); ?>;

        // initialise the translator
        initTranslator(translations);
        // Basic translations
        console.log(translator.translate('translate_this'));
        console.log(translator.translate('name_of_the_author'));
        console.log(translator.translate('error_has_occurred'));

        // Pluralisation
        console.log(translator.translate('x_apples', new Array(), 0));
        console.log(translator.translate('x_apples', new Array(), 1));
        console.log(translator.translate('x_apples', new Array(), 5));

    </script>

</div>

</body>
<?php
