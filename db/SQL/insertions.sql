INSERT INTO `citizens` (`citizenId`, `userId`, `name`, `surname`, `phone`) VALUES
(1, 5, 'CitizenName1', 'CitizenSurname1', '123456789'),
(2, 6, 'CitizenName2', 'CitizenSurname2', '123456788'),
(3, 7, 'CitizenName3', 'CitizenSurname3', '123456787'),
(4, 8, 'CitizenName4', 'CitizenSurname4', '123456786'),
(5, 9, 'CitizenName5', 'CitizenSurname5', '123456785');

INSERT INTO `rescuers` (`rescuerId`, `userId`, `name`, `surname`) VALUES
(1, 2, 'rescuerName1', 'rescuerSurname1'),
(2, 3, 'rescuerName2', 'rescuerSurname2'),
(3, 4, 'rescuerName3', 'rescuerSurname3');

INSERT INTO `users` (`userId`, `username`, `password`, `role`, `lat`, `lng`) VALUES
(1, 'admin', 'password', 'admin', 38.246639, 21.73),
(2, 'rescuer1', 'rescuer1', 'rescuer', 38.25, 21.75),
(3, 'rescuer2', 'rescuer2', 'rescuer', 38.24, 21.73),
(4, 'rescuer3', 'rescuer3', 'rescuer', 38.246639, 21.74),
(5, 'citizen1', 'citizen1', 'citizen', 38.246639, 21.735),
(6, 'citizen2', 'citizen2', 'citizen', 38.246639, 21.745),
(7, 'citizen3', 'citizen3', 'citizen', 38.25, 21.735),
(8, 'citizen4', 'citizen4', 'citizen', 38.24, 21.742),
(9, 'citizen5', 'citizen5', 'citizen', 38.238, 21.73);