<?php
define('ROOT', 'http://localhost:8080/maxapark/public');
define('APP_NAME','MaxaPark');

// ---- Parametros do parque de estacionamento ----
// Horario de funcionamento (parque aberto das 6h as 23h)
define('PARK_ABERTURA', 6);   // hora de abertura
define('PARK_FECHO', 23);     // hora de fecho

// Tarifas base (em Meticais - MZN)
define('TARIFA_HORA', 50);    // valor base por hora
define('TARIFA_MENSAL', 2000);// valor base da mensalidade

// Descontos
define('DESCONTO_MENSAL', 0.25);     // 25% de desconto no pagamento mensal (geral)
define('DESCONTO_ESTUDANTE', 0.50);  // 50% de desconto para estudantes (mensal e por hora)

// Moeda
define('MOEDA', 'MZN');