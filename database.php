<?php

// The SQL to uninstall this tool
$DATABASE_UNINSTALL = array(
    /*
     * "drop table if exists {$CFG->dbprefix}kc_link"
     * We probably want to keep these records even if the tool
     * is uninstalled.
     */
);

// The SQL to create the tables if they don't exist
$DATABASE_INSTALL = array(
    array( "{$CFG->dbprefix}kc_main",
        "create table {$CFG->dbprefix}kc_main (
    SetID       INTEGER NOT NULL AUTO_INCREMENT,
    UserID      INTEGER NULL,
    context_id  INTEGER NULL,
    KCName 		varchar(255) NULL,
    Modified    datetime NULL,
    Active      int(1) DEFAULT '0',
    Visible     int(1) DEFAULT '1',
  
    PRIMARY KEY(SetID)
) ENGINE = InnoDB DEFAULT CHARSET=utf8"),
    array( "{$CFG->dbprefix}kc_link",
        "create table {$CFG->dbprefix}kc_link (
    link_id     INTEGER NOT NULL,
    SetID       INTEGER NULL,

    CONSTRAINT `{$CFG->dbprefix}kc_link_ibfk_2`
        FOREIGN KEY (`SetID`)
        REFERENCES `{$CFG->dbprefix}kc_main` (`SetID`)
        ON UPDATE CASCADE,
        
    PRIMARY KEY(link_id)
) ENGINE = InnoDB DEFAULT CHARSET=utf8"),
    array( "{$CFG->dbprefix}kc_questions",
        "create table {$CFG->dbprefix}kc_questions (
    QID      INTEGER NOT NULL AUTO_INCREMENT,
    SetID       INTEGER NULL,
    QNum     INTEGER NULL,
    Question       varchar(1500) NULL,
    Answer       varchar(100) NULL,
    A        varchar(255) NULL,
    B        varchar(255) NULL,
    C        varchar(255) NULL,
    D     varchar(255) NULL,
    Modified    datetime NULL,
    QType       varchar(10) DEFAULT 'Text',
  
    CONSTRAINT `{$CFG->dbprefix}kc_ibfk_1`
        FOREIGN KEY (`SetID`)
        REFERENCES `{$CFG->dbprefix}kc_main` (`SetID`)
        ON UPDATE CASCADE,

    PRIMARY KEY(QID)
) ENGINE = InnoDB DEFAULT CHARSET=utf8"),
    array( "{$CFG->dbprefix}kc_activity",
        "create table {$CFG->dbprefix}kc_activity (
    ActivityID  INTEGER NOT NULL AUTO_INCREMENT,
    UserID      INTEGER NULL,
    SetID       INTEGER NULL,
    QID      	INTEGER NOT NULL,
	Answer      varchar(100) NULL,
	Attempt    	INTEGER NULL,
    Modified    datetime NULL,
  
    PRIMARY KEY(ActivityID)
) ENGINE = InnoDB DEFAULT CHARSET=utf8"),
    array( "{$CFG->dbprefix}kc_activity",
        "create table {$CFG->dbprefix}kc_attempt (
    AttemptID  INTEGER NOT NULL AUTO_INCREMENT,
    UserID      INTEGER NULL,
    SetID       INTEGER NULL,
    Modified    datetime NULL,
  
    PRIMARY KEY(AttemptID)
) ENGINE = InnoDB DEFAULT CHARSET=utf8"),
    array( "{$CFG->dbprefix}kc_students",
        "create table {$CFG->dbprefix}kc_attempt (
    StudentID  INTEGER NOT NULL AUTO_INCREMENT,
    UserID      INTEGER NULL,
    SetID       INTEGER NULL,
	LastName    varchar(100) NULL,
	FirstName   varchar(100) NULL,
    Modified    datetime NULL,
  
    PRIMARY KEY(AttemptID)
) ENGINE = InnoDB DEFAULT CHARSET=utf8")
);