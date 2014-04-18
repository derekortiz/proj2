The html files are an example of what the interface will resemble when implemented. The backend and some
front end has yet to be implemented and is just an attempted mock up of what things will look like.

*** RELATIVE PATH TO CSS SET TO "./css/login.css" ***
so you'll either have to change the path or give it folder when you download it



  In implementation index will load upon attempt to load anywhere in the webpage
forcing the user to log in and depending on login info access and visibility of the admin forms
will be handled by the php backend.

functions will include 
- Login: taking in User Id and Password combo to validate users and permissions
         and loading appropriate home page.
		 
-account page: function will check that old password is correct and update the current users password
         client side validation will ensure new password is correct in both boxes before changing.
		 
-admin page: on load it will pull all students info into a big table. Reset Password button will change
		corresponding password on table row in the database to a 'default' or random generate one and email
		it if we implement email into our database later. Client side verification of the reset will happen
		upon clicking the reset password button.
		The edit button will populate the edit user page with that users information.
		The x deletes the user from the database. A prompt will make sure this is the intended behaviour.

-edit user page: After being populated the admin can change the info in the boxes and submit those 
		changes to the server that will then update the database for that user.
		
-in addition there will be a function to sanitize input to the fields to prevent injection

	The schedule page will be used and default for student when we get around to part two but until then it's
just a place holder for later in the project. Currently the log in button just defaults to account without
any processing.

