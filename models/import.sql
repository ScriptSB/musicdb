load data local infile "/Users/anydea/Desktop/intro\ to\ database\ systems/ProjectData/area.csv"
into table Init_Area
columns terminated by '\t'
lines terminated by '\n'
ignore 1 lines;

insert into Init_Area values (0,null,null);

set foreign_key_checks = 0;
load data local infile "/Users/anydea/Desktop/intro\ to\ database\ systems/ProjectData/artist.csv"
into table Init_Artist
columns terminated by '\t'
lines terminated by '\n'
ignore 1 lines;
set foreign_key_checks = 1;


load data local infile "/Users/anydea/Desktop/intro\ to\ database\ systems/ProjectData/genre.csv"
into table Init_Genre
columns terminated by '\t'
lines terminated by '\n'
ignore 1 lines;