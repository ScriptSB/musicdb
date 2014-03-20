--ENTITY area
CREATE TABLE  area (
  AID INTEGER,
  Aname CHAR(45),
  Atype CHAR(45),
  PRIMARY KEY (AID));
  
--ENTITY genre
--we don't need gcount in genre
CREATE TABLE  genre (
  GID INTEGER,
  Gname CHAR(45),
  PRIMARY KEY (GID));

--ENTITY artist
CREATE TABLE  artist (
  arID INTEGER,
  name CHAR(45) NULL,
  type CHAR(45) NULL,
  gender CHAR(45) NULL,
  PRIMARY KEY (arID));
  
--ENTITY recording
CREATE TABLE  recording (
  RID INTEGER,
  Rname CHAR(45),
  Rlength CHAR(45),
  PRIMARY KEY (RID));

--ENTITY release
CREATE TABLE  release (
  REID INTEGER,
  Relname CHAR(45),
  PRIMARY KEY (REID));

--ENTITY medium
CREATE TABLE  medium (
  MID INTEGER,
  Mformat CHAR(45),
  release_REID INTEGER,
  PRIMARY KEY (MID));

--relationship track
CREATE TABLE  track (
  TID INTEGER,
  position INTEGER,
  MID INTEGER,
  REID INTEGER,
  PRIMARY KEY (TID),
  UNIQUE(MID, REID),
  FOREIGN KEY (MID) from medium,
  FOREIGN KEY (REID) from release);
    
--RELATIONSHIP artist-area
CREATE TABLE artist_area (
  arID INTEGER NOT NULL,
  AID INTEGER,
  PRIMARY KEY (arID, AID),
  FOREIGN KEY (arID) REFERENCES artist,
  FOREIGN KEY (AID) REFERENCES area);

--RELATIONSHIP artist_genre
CREATE TABLE artist_genre (
  arID INTEGER NOT NULL,
  GID INTEGER NOT NULL,
  PRIMARY KEY (arID, GID),
  FOREIGN KEY (arID) REFERENCES artist,
  FOREIGN KEY (GID) REFERENCES genre);
    
--RELATIONSHIP using
CREATE TABLE using(
  MID INTEGER,
  REID INTEGER,
  PRIMARY KEY (MID, REID),
  FOREIGN KEY (MID) REFERENCES media,
  FOREIGN KEY (REID) REFERENCES recording);
  
--RELATIONSHIP artist_track
CREATE TABLE participate(
  arID INTEGER NOT NULL,
  TID INTEGER NOT NULL,
  PRIMARY KEY (arID, TID),
  FOREIGN KEY (arID) REFERENCES artist,
  FOREIGN KEY (TID) REFERENCES track);

