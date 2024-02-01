INSERT INTO `citizens` (`citizenId`, `userId`, `name`, `surname`, `phone`) VALUES
(1, 3, 'CitizenName', 'CitizenSurname', '123456789');

INSERT INTO `rescuers` (`rescuerId`, `userId`, `name`, `surname`) VALUES
(1, 2, 'rescuerName1', 'rescuerSurname1'),
(2, 4, 'rescuerName2', 'rescuerSurname2');

INSERT INTO `users` (`userId`, `username`, `password`, `role`, `lat`, `lng`) VALUES
(1, 'admin', 'password', 'admin', 38.246639, 21.73),
(2, 'rescuer1', 'rescuer1', 'rescuer', 38.246639, 21.74),
(3, 'citizen1', 'citizen', 'citizen', 38.246639, 21.735),
(4, 'rescuer2', 'rescuer2', 'rescuer', 38.246639, 21.74);