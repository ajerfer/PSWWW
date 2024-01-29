INSERT INTO `citizens` (`citizenId`, `userId`, `name`, `surname`, `phone`, `address`) VALUES
(1, 3, 'CitizenName', 'CitizenSurname', '123456789', 'CitizenAddress');

INSERT INTO `rescuers` (`rescuerId`, `userId`, `name`, `surname`) VALUES
(1, 2, 'rescuerName', 'rescuerSurname');

INSERT INTO `users` (`userId`, `username`, `password`, `role`) VALUES
(1, 'admin', 'password', 'admin'),
(2, 'rescuer1', 'rescuer', 'rescuer'),
(3, 'citizen1', 'citizen', 'citizen');