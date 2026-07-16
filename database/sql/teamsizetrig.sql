CREATE OR REPLACE TRIGGER trg_team_size_check
BEFORE INSERT ON TEAM_POKEMON
FOR EACH ROW
DECLARE
    v_count NUMBER;
BEGIN
    SELECT COUNT(*) INTO v_count
    FROM TEAM_POKEMON
    WHERE team_id = :NEW.team_id;

    IF v_count >= 6 THEN
        RAISE_APPLICATION_ERROR(-20001, 'A team cannot have more than 6 Pokémon.');
    END IF;
END;
/