API Symfony to communication with external api to store and update exchange rates.

Fetch actual exchange rates
GET /fetch_currencies?table_name=b
Parameter table_name is optional (default is a). 


GET Collection 
/api/currencies


GET item
/api/currencies/{uuid}
ex. /api/currencies/018eccc9-2f1c-780b-afa9-af72f1f97c67


POST
/api/currencies
{
	"name": "test",
	"currencyCode": "TST",
	"exchangeRate": "10.25"
}
