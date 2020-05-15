<?php

$REGISTER_LTI2 = array(
    "name" => "Knowledge Check", // Name of the tool
    "FontAwesome" => "fa-check", // Icon for the tool
    "short_name" => "Knowledge Check",
    "description" => "Create small quizzes that include multiple choice or true/false questions for students to use to assess their knowledge after completing a reading, watching a video, or in preparation for an upcoming test.", // Tool description
    "messages" => array("launch"),
    "privacy_level" => "public",  // anonymous, name_only, public
    "license" => "Apache",
    "languages" => array(
        "English",
    ),
    "source_url" => "https://github.com/udaytonapps/knowledgecheck",
    // For now Tsugi tools delegate this to /lti/store
    "placements" => array(
        /*
        "course_navigation", "homework_submission",
        "course_home_submission", "editor_button",
        "link_selection", "migration_selection", "resource_selection",
        "tool_configuration", "user_navigation"
        */
    ),
    "screen_shots" => array(
    )
);
