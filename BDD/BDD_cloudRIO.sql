USE cloudRIO;

DROP TABLE Music_to_playlist;
DROP TABLE d_Partage;
DROP TABLE f_Partage;
DROP TABLE playlist_Partage;
DROP TABLE Playlist;
DROP TABLE Fichiers;
DROP TABLE Dossiers;
DROP TABLE Utilisateurs;

CREATE TABLE Utilisateurs
(
    uid VARCHAR(254),
    email VARCHAR(254),
    nom VARCHAR (254),
    prenom VARCHAR(254),
    mdp VARCHAR(254), /*haché*/
    space_allow INT DEFAULT 10, /*10GB*/
    lien_dossier_init VARCHAR(254),
    date_inscription DATE, 
    
    PRIMARY KEY (uid)
);

/*###################### Dossiers #############################*/

CREATE TABLE Dossiers
(
    iddossiers INT,
    d_nom VARCHAR (254),
    d_chemin VARCHAR (254) NOT NULL UNIQUE,
    proprietaire VARCHAR (254),
	FOREIGN KEY (proprietaire) REFERENCES Utilisateurs (uid) ON DELETE CASCADE,
    dossier_parent INT  DEFAULT NULL,
    FOREIGN KEY (dossier_parent) REFERENCES Dossiers (iddossiers) ON DELETE CASCADE,

    PRIMARY KEY(iddossiers) 
);

CREATE TABLE d_Partage
(
    iddossiers INT,
    FOREIGN KEY (iddossiers) REFERENCES Dossiers (iddossiers) ON DELETE CASCADE,
    share_person VARCHAR(254),
    /*Peut prendre la valeur ALL*/
    FOREIGN KEY (share_person) REFERENCES Utilisateurs (uid) ON DELETE CASCADE,
    Droit INT, 
    /*de la forme READ/WRITE (0 1 2 ou 3) sachant que le 0 n'est pas vraiment utile*/
    /*WRITE signifie téléchargement/chargement autorisé*/

    PRIMARY KEY (iddossiers,share_person)
);
/*###################### Fichiers #############################*/

CREATE TABLE Fichiers 
(
    idfichiers INT,
    f_nom VARCHAR (254),
    f_chemin VARCHAR (254) NOT NULL UNIQUE,
    f_dossier_parent INT,
    FOREIGN KEY (f_dossier_parent) REFERENCES Dossiers (iddossiers) ON DELETE CASCADE,
    f_type VARCHAR(255), /*image, audio, vidéo, etc...*/
    taille INT, /*en octets*/

    PRIMARY KEY (idfichiers)
);

CREATE TABLE f_Partage
(
    idfichiers INT,
    FOREIGN KEY (idfichiers) REFERENCES Fichiers (idfichiers) ON DELETE CASCADE,
    share_person VARCHAR(254),
    /*Peut prendre la valeur ALL*/
    FOREIGN KEY (share_person) REFERENCES Utilisateurs (uid) ON DELETE CASCADE,
    Droit INT, 
    /*de la forme READ/WRITE (0 1 2 ou 3) sachant que le 0 n'est pas vraiment utile
    WRITE signifie téléchargement autorisé*/

    PRIMARY KEY (idfichiers,share_person)
);


/*###################### Musique #############################*/

CREATE TABLE Playlist
(
    idPlaylist INT,
    play_nom VARCHAR(254),
    play_proprietaire VARCHAR (254),

    PRIMARY KEY(idPlaylist)
);

CREATE TABLE playlist_Partage
(
    idPlaylist INT,
    FOREIGN KEY (idPlaylist) REFERENCES Playlist (idPlaylist) ON DELETE CASCADE,
    share_person VARCHAR(254),
    /*Peut prendre la valeur ALL*/
    FOREIGN KEY (share_person) REFERENCES Utilisateurs (uid) ON DELETE CASCADE,
    Droit INT, 
    /*de la forme READ/WRITE (0 1 2 ou 3) sachant que le 0 n'est pas vraiment utile. 
    WRITE signifie téléchargement autorisé*/

    PRIMARY KEY (idPlaylist,share_person)
);


CREATE TABLE Music_to_playlist
(
    idPlaylist INT,
    FOREIGN KEY (idPlaylist) REFERENCES Playlist (idPlaylist) ON DELETE CASCADE,
    idMusique INT,
    FOREIGN KEY (idMusique) REFERENCES Fichiers (idfichiers) ON DELETE CASCADE,

    PRIMARY KEY(idPlaylist,idMusique)
);

