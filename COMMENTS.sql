
--comments TRIGGER: cap nhat avg_rating trong products 

--TRIGGER FUNCTION-------------------------------------
CREATE OR REPLACE FUNCTION avg_rate()
  RETURNS trigger AS
$BODY$ declare
avg_rating_tmp numeric(12,2);
begin

if TG_OP = 'INSERT' then

	select avg("rating") into avg_rating_tmp 
	from products p natural join comments cm
	where prod_id = NEW.prod_id;

	update products
	set avg_rating = avg_rating_tmp
	where prod_id = NEW.prod_id;
	
end if;
return NEW;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION avg_rate()
  OWNER TO postgres;

--TRIGGER----------------------------------------
CREATE TRIGGER tg_avgrate
  AFTER INSERT
  ON comments
  FOR EACH ROW
  EXECUTE PROCEDURE avg_rate();

