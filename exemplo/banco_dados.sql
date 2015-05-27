CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `sobrenome` varchar(50) NOT NULL,
  `idade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;