CREATE INDEX task_idx_01 ON `task` (`task_id_fk`);
CREATE INDEX task_idx_02 ON `task` (`task_type_id_fk`);
CREATE INDEX task_idx_03 ON `task` (`left`);
CREATE INDEX task_idx_04 ON `task` (`right`);
CREATE INDEX task_idx_05 ON `task` (`order_number`);
CREATE INDEX task_idx_06 ON `task` (`level`);