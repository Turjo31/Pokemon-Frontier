CREATE OR REPLACE TRIGGER trg_update_ranking
AFTER INSERT ON MATCH_RESULT
FOR EACH ROW
DECLARE
    v_league_id NUMBER;
BEGIN
    SELECT league_id INTO v_league_id
    FROM TOURNAMENT
    WHERE tournament_id = :NEW.tournament_id;

    -- Update winner
    MERGE INTO RANKING r
    USING DUAL ON (r.trainer_id = :NEW.winner_id AND r.league_id = v_league_id)
    WHEN MATCHED THEN
        UPDATE SET points = points + 50, wins = wins + 1, last_updated = SYSTIMESTAMP
    WHEN NOT MATCHED THEN
        INSERT (trainer_id, league_id, points, wins, losses)
        VALUES (:NEW.winner_id, v_league_id, 50, 1, 0);

    -- Update loser
    MERGE INTO RANKING r
    USING DUAL ON (
        r.trainer_id = CASE WHEN :NEW.winner_id = :NEW.trainer1_id
                            THEN :NEW.trainer2_id
                            ELSE :NEW.trainer1_id END
        AND r.league_id = v_league_id
    )
    WHEN MATCHED THEN
        UPDATE SET points = GREATEST(points - 20, 0), losses = losses + 1, last_updated = SYSTIMESTAMP
    WHEN NOT MATCHED THEN
        INSERT (trainer_id, league_id, points, wins, losses)
        VALUES (
            CASE WHEN :NEW.winner_id = :NEW.trainer1_id
                 THEN :NEW.trainer2_id
                 ELSE :NEW.trainer1_id END,
            v_league_id, 0, 0, 1
        );
END;
/