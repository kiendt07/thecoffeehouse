
--orderlines TRIGGER: Tinh toan cot total trong bang orders

--TRIGGER FUNCTION--------------------------------
CREATE OR REPLACE FUNCTION sum_order()
  RETURNS trigger AS
$BODY$ declare
sum_tmp numeric(12,2);
begin

select 
	sum(p.price * ol.quantity) into sum_tmp 
from 
	orders o, orderlines ol, products p
where 
	o.orderid = ol.orderid 
	and ol.orderid = NEW.orderid and p.prod_id = ol.prod_id;

if TG_OP = 'INSERT' then
	update orders
	set total = sum_tmp
	where orderid = NEW.orderid;
end if;
return NEW;
end;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
ALTER FUNCTION sum_order()
  OWNER TO postgres;

--TRIGGER---------------------------------------------------
CREATE TRIGGER tg_sum_order
  AFTER INSERT
  ON orderlines
  FOR EACH ROW
  EXECUTE PROCEDURE sum_order();
