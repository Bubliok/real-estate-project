


CREATE OR REPLACE FUNCTION getfilteredproperties(
    cityid integer,
    listingtype text,
    minbedrooms integer DEFAULT NULL,
    minbathrooms integer DEFAULT NULL
)
    RETURNS TABLE (
                      id integer,
                      name text,
                      price numeric,
                      city_id integer,
                      listing_type text,
                      bedrooms integer,
                      bathrooms integer
                  ) AS $$
BEGIN
    RETURN QUERY
        SELECT
            p.id,
            p.name::text,
            p.price::numeric,
            p.city_id_id,
            p.listing_type::text,
            r.bedrooms,
            r.bathrooms
        FROM property p
                 LEFT JOIN residential r ON p.id = r.property_id
        WHERE p.city_id_id = cityid
          AND p.listing_type = listingtype
          AND (r.bedrooms >= minbedrooms OR minbedrooms IS NULL)
          AND (r.bathrooms >= minbathrooms OR minbathrooms IS NULL);
END;
$$ LANGUAGE plpgsql;

DROP FUNCTION IF EXISTS getfilteredproperties(integer, text, integer, integer);

SELECT * FROM getfilteredproperties(2, 'sale', 0, 0);

CREATE OR REPLACE FUNCTION countpropertiesbycityandtype(cityid integer, listingtype text)
    RETURNS integer AS $$
DECLARE
    result integer;
BEGIN
    SELECT COUNT(*) INTO result
    FROM property
    WHERE city_id_id = cityid
      AND listing_type = listingtype;
    RETURN result;
END;
$$ LANGUAGE plpgsql;

SELECT countpropertiesbycityandtype(2, 'sale');