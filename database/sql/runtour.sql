CREATE OR REPLACE PROCEDURE run_tournament_round(p_tournament_id IN NUMBER) IS
BEGIN
    FOR match IN (
        SELECT match_id FROM MATCH_RESULT
        WHERE tournament_id = p_tournament_id
        AND winner_id IS NULL
    ) LOOP
        simulate_match(match.match_id);
    END LOOP;
    COMMIT;
END;
/