<?php

return array(
    'lexicon' => array(
        'simple_nlg' => array(
            'xml' => array(
                'en' => 'english-lexicon.xml',
                'fr' => 'french-lexicon.xml'
            ),
            'xsl' => 'lexicon.xsl',
            'patch' => array(
                'en' => 'english-lexicon-patch.json',
                'fr' => 'french-lexicon-patch.json'
            ),
            'extension' => array(
                'en' => 'english-lexicon-extension.json',
                'fr' => 'french-lexicon-extension.json'
            )
        ),
        'public' => array(
            'uncompressed' => array(
                'en' => 'lex-en.json',
                'fr' => 'lex-fr.json'
            ),
            'compressed' => array(
                'en' => 'lex-en.min.json',
                'fr' => 'lex-fr.min.json'
            )
        )
    ),
    'verb' => array(
        'dictionary' => array(
            'xml' => array(
//                'en' => '',
                'fr' => 'french-verb.xml'
            ),
            'xsl' => array(
//                'en' => '',
                'fr' => 'french-verb.xsl'
            )
        ),
        'conjugation' => array(
            'xml' => array(
//                'en' => '',
                'fr' => 'french-verb-conjugation.xml'
            ),
            'xsl' => array(
//                'en' => '',
                'fr' => 'french-verb-conjugation.xsl'
            )
        )
    ),
    /**
     * Grammar, Conjugation...
     */
    'rule' => array(
        'public' => array(
            'uncompressed' => array(
                'en' => 'rule-en.json',
                'fr' => 'rule-fr.json'
            ),
            'compressed' => array(
                'en' => 'rule-en.min.json',
                'fr' => 'rule-fr.min.json'
            )
        )
    )
);