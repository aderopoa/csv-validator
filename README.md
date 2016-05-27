# csv-validator
A script to validate CSV 

# To run the script
 1. Create a CSV file using the header as the object variables.
 2. Update `$rowValidation` on `validate-csv-script.php` file to how validation is to be done on created csv.
 3. Run the script in this format `php validate-csv-script.php <csv_file>`, example `php validate-csv-script.php test.csv`.
 4. Output will be displayed in json format. Output contains json array of objects created.
 ```{"1":{"object":{"id":"1","name":"Jacob Ayokunle","phone":"08098762991","email":"jacob@ayokunle.com","user_name":"jacob.ayokunle","comment":"This is a comment."}},"2":{"object":{"id":"2","name":"Abdul Ibrahim","phone":"07032221828","errors":[{"email":"bahd.duek@hdd failed validation for email"}],"user_name":"kounseie23","comment":"comment"}},"3":{"object":{"id":"3","name":"ieuef hdhd4l","errors":[{"minCount":"093833 failed validation for minCount"}],"email":"jdh@hsh.coq","user_name":"username","comment":"co47&992 mment"}}}```

# CSV to Object Pseudocode
 - Check if file is CSV.
 - If file is not CSV return an error else load CSV.
 - Get validation parameters for each column
 - Check how many columns are on the csv.
 - Start looping through the rows.
 - If first row, sanitize each column in the row and assign value to an array as object variables, then continue.
 - Else, validate each column in row against validation rules and assign value to object matching the index to the object variable index and save error to errors.
 - Save the object to array of objects.
 - Display objects or use as desired.

