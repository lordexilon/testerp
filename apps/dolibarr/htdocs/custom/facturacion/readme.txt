Facturacion Argentina
Application for print n items lines per page in Argentina Billing.

This file describes the steps that you need to follow to deploying the application:

1. Extract facturacion.zip and copy the folder in htdocs (Dolibarr Folder).
2. Create a new Menu in Configuration. The menu name Ex. "Facturacion Argentina".
3. Add the next parameters in Dolibarr Info:
	FACTURE_USE_ARGENTINA Value = 1 Entity=1
	CANT_LINEAS_FC Value = n (n=number of lines per page) Entity=1
4. End

