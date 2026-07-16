CREATE OR REPLACE FUNCTION get_team_power(p_team_id IN NUMBER)
RETURN NUMBER IS
    v_power NUMBER := 0;
BEGIN
    SELECT SUM(p.hp + p.attack + p.defense + p.speed)
    INTO v_power
    FROM TEAM_POKEMON tp
    JOIN POKEMON p ON p.pokemon_id = tp.pokemon_id
    WHERE tp.team_id = p_team_id;

    RETURN NVL(v_power, 0);
END;
/