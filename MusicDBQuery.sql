--a
select artist.ANAME
from init_area area, init_artist artist
where area.areaname='Switzerland';

--b
select area.areaname
from INIT_AREA area
where area.AREAID in (select art.areaid
                      from INIT_ARTIST art
                      where art.GENDER = 'Male' and art.ATYPE = 'Person' and art.AREAID <> 0
                      GROUP BY art.AREAID 
                      having count(*)>= all (select count(*)
                                             from INIT_ARTIST art1
                                             where art1.GENDER = 'Male' and art1.ATYPE = 'Person'and art.AREAID <> 0
                                             group by art1.AREAID
                                            )
                     );
             
select count(*)
from INIT_ARTIST art
where art.ATYPE='Person' and art.GENDER ='Female' and art.areaid = '222' --United States

--c
select art.aname
from
(select rec1.RID
from init_recording rec1, init_track track1
where rec1.RID = track1.REID 
group by rec1.RID
order by count(*)desc) recorded, init_recording rec, init_track track, init_artist art, init_artist_track art_track
where recorded.RID = rec.RID and art.AID = art_track.AID and art_track.TID = track.TID 
      and rec.RID = track.REID and art.atype = 'Group' and rownum <=10;
      
--d
select art.ANAME
from (select art.aid
      from INIT_ARTIST art 
           join INIT_ARTIST_TRACK art_track on art.AID = art_track.AID
           join INIT_TRACK track on art_track.TID = track.TID
           join INIT_MEDIUM medium on track.MID = medium.MID
           join INIT_RELEASE release on medium.RID = release.RID
      where art.atype = 'Group'
      group by art.aid
      order by count(*) desc) info, init_ARTIST art
WHERE art.AID = info.AID and rownum <= 10
ORDER BY rownum;

--e Print the name of a female artist associated with the most genres.
select art1.aname
from
(select art.aid
from init_artist art, init_artist_genre art_genre, init_genre genre
where art.aid = art_genre.aid and art_genre.GID = genre.GID and art.gender = 'Female' 
group by art.aid
order by count(*)) genreRank, init_artist art1
where  genreRank.aid = art1.aid and rownum = 1;

--f

--QueryF
select area.areaname
from INIT_AREA area--, INIT_ARTIST artist
where area.area_type='City' and (select count(*)
                                  from INIT_ARTIST artist
                                  where artist.AREAID = area.areaID and artist.GENDER='Male')<(select count(*)
                                  from INIT_ARTIST artist,INIT_AREA area
                                  where artist.AREAID = area.areaID and artist.GENDER='Female');


--Query G
create view med_track as
select medium.MID,count(*) as tracks
from INIT_Medium medium,INIT_TRACK track
where track.MID = medium.MID
group by medium.MID order by count(*) desc;

select med_track.mid,med_track.tracks
from  med_track 
where med_track.tracks =(
  select MAX(med_track.tracks)
  from  med_track 
  );


