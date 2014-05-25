--Query B
create view area_male as
select area.AREAID,area.areaname,count(*)as sum
from INIT_Area area, INIT_ARTIST artist
where artist.AREAID = area.AREAID and area.AREAID<>'0' and artist.GENDER='Male'
GROUP BY area.areaID, area.Areaname
order by count(*) desc;

create view area_female as
select area.AREAID,area.areaname,count(*)as sum
from INIT_Area area, INIT_ARTIST artist
where artist.AREAID = area.AREAID and area.AREAID<>'0' and artist.GENDER='Female'
GROUP BY area.areaID, area.areaname
order by count(*) desc;

create view area_group as
select area.AREAID,area.areaname,count(*)as sum
from INIT_Area area, INIT_ARTIST artist
where artist.AREAID = area.AREAID and area.AREAID<>'0' and artist.atype='Group'
GROUP BY area.areaID, area.areaname
order by count(*) desc;

select area_male.areaname,area_male.sum as MALE,area_female.sum as FEMALE,area_group.sum as GROU
from AREA_MALE,AREA_GROUP,AREA_FEMALE
where (AREA_FEMALE.AREAname=AREA_MALE.AREAname and AREA_FEMALE.AREAname=AREA_Group.AREAname and AREA_FEMALE.AREANAME=(select area_female.areaname
                                                                                                                      from area_female
                                                                                                                      where ROWNUM=1))or 
      (AREA_MALE.AREAname=AREA_FEMALE.AREAname and AREA_MALE.AREAname=AREA_Group.AREAname and AREA_MALE.AREANAME=(select area_male.areaname
                                                                                                                      from area_male
                                                                                                                      where ROWNUM=1))or
      (AREA_GROUP.AREAname=AREA_MALE.AREAname and AREA_FEMALE.AREAname=AREA_Group.AREAname and AREA_GROUP.AREANAME=(select area_group.areaname
                                                                                                                      from area_group
                                                                                                                      where ROWNUM=1));                                                                                                               
      
--Query C
select artist.aname
from(
select artist.ANAME,count(*)
from init_artist artist,init_artist_track A_T
where artist.atype='Group' and A_T.aid=artist.aid
group by artist.aname order by count(*) desc) artist
where Rownum<11;

--Query D


--Query H
select artist.area_name, artist.gender,artist.name
from artist_track at1, ARTIST artist
where artist.ID=at1.AID and artist.area_name in (select a1.area_name
                                    from artist a1 
                                    where a1.area_name is not null
                                    group by a1.area_name
                                    having count(*)>30)
group by artist.area_name, artist.gender, artist.name order by count(*) desc;


--Query I
select recording.name
from RECORDING ,(select track.rcid, rank() over (order by count(distinct track.mid) desc) as rank
from TRACK track
where exists (select artrack.TID
              from ARTIST artist, ARTIST_TRACK artrack
              where artist.name = 'Metallica' and  artrack.aid=artist.id and track.tid = artrack.tid)
group by track.rcid) toptrak 
where toptrak.rank<=25 and recording.id = toptrak.rcid;


--Query J
from ARTIST_GENRE ag2, (select ag1.gid as gid, rank() over (order by count(*) desc) as rank
from Artist_GENRE ag1
group by ag1.gid) genrelst
where area.rank<=10 and ag2.gid = genrelst.gid
                  
                  
