CREATE OR REPLACE PROCEDURE simulate_match(p_match_id IN NUMBER) IS
    v_team1_id     NUMBER;
    v_team2_id     NUMBER;
    v_trainer1_id  NUMBER;
    v_trainer2_id  NUMBER;
    v_tournament_id NUMBER;
    v_score1       NUMBER;
    v_score2       NUMBER;
    v_winner_id    NUMBER;
BEGIN
    SELECT trainer1_id, trainer2_id, team1_id, team2_id, tournament_id
    INTO v_trainer1_id, v_trainer2_id, v_team1_id, v_team2_id, v_tournament_id
    FROM MATCH_RESULT
    WHERE match_id = p_match_id;

    v_score1 := ROUND(get_team_power(v_team1_id) * (0.85 + DBMS_RANDOM.VALUE * 0.3));
    v_score2 := ROUND(get_team_power(v_team2_id) * (0.85 + DBMS_RANDOM.VALUE * 0.3));

    IF v_score1 >= v_score2 THEN
        v_winner_id := v_trainer1_id;
    ELSE
        v_winner_id := v_trainer2_id;
    END IF;

    UPDATE MATCH_RESULT
    SET team1_score = v_score1,
        team2_score = v_score2,
        winner_id   = v_winner_id
    WHERE match_id = p_match_id;

    COMMIT;
END;
/