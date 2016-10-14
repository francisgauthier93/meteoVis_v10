<?php

return array(
    'download' => array(
        'message' => array(
            'fr' => 'Impossible de télécharger le fichier demandé'
        ),
        'code' => 4001
    ),
//    'xml_parse' => array(
//        'message' => array(
//            'fr' => array(
//                'html' => 'Le fichier Xml est mal form&eacute;',
//                'text' => 'Le fichier Xml est mal formé'
//            )
//        ),
//        'code' => 4002
//    ),
    /**
     * Les parametres POST ou GET sont incorrects
     */
    'wrong_parameter' => array(
        'message' => array(
            'fr' => 'Les paramètres sont incorrects'
        ),
        'code' => 4003
    ),
    /**
     * Les arguments d'une methode ou fonction sont incorrects
     */
    'wrong_argument' => array(
        'message' => array(
            'fr' => 'Les arguments sont incorrects'
        ),
        'code' => 4004
    ),
    /**
     * Le template n'existe pas
     */
    'tpl_not_found' => array(
        'message' => array(
            'fr' => 'La page est introuvable'
        ),
        'code' => 4005
    ),
    /**
     * Le lieu n'existe pas en base de données
     */
    'location_not_exists' => array(
        'message' => array(
            'fr' => 'Ce lieu n\'existe pas'
        ),
        'code' => 4006
    ),
    /**
     * Une action n'existe pas dans l'API
     */
    'action_not_exists' => array(
        'message' => array(
            'fr' => 'Impossible de réaliser cette action, elle n\'est pas supportée par l\'API.'
        ),
        'code' => 4007
    ),
    /**
     * Fichier introuvable, chargement impossible
     */
    'file_not_found' => array(
        'message' => array(
            'fr' => 'Le fichie est introuvable, impossible de le charger.'
        ),
        'code' => 4008
    ),
    /**
     * Langue non supportee par l'application
     */
    'language_not_supported' => array(
        'message' => array(
            'fr' => 'Cette langue n\'est pas supportée par l\'application.',
        ),
        'code' => 4009
    ),
    /**
     * Le JSON est invalide, problème de syntaxe
     */
    'invalid_json' => array(
        'message' => array(
            'fr' => 'Le JSON est mal formaté.',
        ),
        'code' => 4009
    ),
    /**
     * Echec lors de la creation du fichier
     */
    'file_not_created' => array(
        'message' => array(
            'fr' => 'Impossible de créer ce fichier.',
        ),
        'code' => 4010 
   ),
    /**
     * Le XML est invalide, problème de syntaxe
     */
    'invalid_xml' => array(
        'message' => array(
            'fr' => 'Le XML est mal formaté.',
        ),
        'code' => 4010 
   )
);