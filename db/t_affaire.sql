CREATE TABLE IF NOT EXISTS t_affaire (
	id INT(11) NOT NULL AUTO_INCREMENT,
	dateRdv DATE DEFAULT NULL,
	heureRdv TIME DEFAULT NULL,
	dateSortie DATE DEFAULT NULL,
	nature VARCHAR(100) DEFAULT NULL,
	prix DECIMAL(12,2) DEFAULT NULL,
	paye DECIMAL(12,2) DEFAULT NULL,
	mandataire VARCHAR(50) DEFAULT NULL,
	status VARCHAR(50) DEFAULT NULL,
	idTopographe INT(12) DEFAULT NULL,
	idSource INT(12) DEFAULT NULL,
	idService INT(12) DEFAULT NULL,
	idClient INT(12) DEFAULT NULL,
	province VARCHAR(50) DEFAULT NULL,
	mp VARCHAR(50) DEFAULT NULL,
	cr VARCHAR(50) DEFAULT NULL,
	quartier VARCHAR(255) DEFAULT NULL,
	sousquartier VARCHAR(255) DEFAULT NULL,
	propriete VARCHAR(100) DEFAULT NULL,
	montantTopographe DECIMAL(12,2) DEFAULT NULL,
	montantService DECIMAL(12,2) DEFAULT NULL,
	montantSource DECIMAL(12,2) DEFAULT NULL,
	created DATETIME DEFAULT NULL,
	createdBy VARCHAR(50) DEFAULT NULL,
	updated DATETIME DEFAULT NULL,
	updatedBy VARCHAR(50) DEFAULT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;