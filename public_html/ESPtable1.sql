SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


CREATE TABLE `id16116035_coraldefensedb`.`ESPtable1` ( `timeid` TIME NOT NULL DEFAULT CURRENT_TIMESTAMP ,  `temperature` FLOAT(5) NOT NULL ,  `humidity` FLOAT(5) NOT NULL ,  `flow_frequncy` FLOAT(5) NOT NULL ,  `flow_total_ml` FLOAT(5) NOT NULL ,  `flow_rate_ml` FLOAT(5) NOT NULL ,  `hall_effect_state` BIT(1) NOT NULL ,  `level_switch_state` BIT(1) NOT NULL ,  `uv_state` BIT(1) NOT NULL ) ENGINE = InnoDB;

INSERT INTO `ESPtable1` (`timeid`, `temperature`, `humidity`, `flow_frequency`, `flow_total_ml`, `flow_rate_ml`, `hall_effect_state`, `level_switch_state`, `uv_state`) VALUES
(00:00:00, 0, 0, 0, 0, 0, 0, 0, 0);

ALTER TABLE `ESPtable1`
  ADD PRIMARY KEY (`timeid`);
