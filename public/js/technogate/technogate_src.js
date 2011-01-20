/**
 * Technogate
 *
 * @package technogate
 * @author Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */

/** Create a NameSpace to avoid conflict */
TECHNOGATE_JS = {};

/**
 * This function shows an error in standard Technogate form.
 * expected behaviour:
 *
 * - The form's class is either technogateForm or technogateForm1Col
 * - The fields are a child of an li that has the same id plus Field, and
 *   a div with the fieldZone class i.e:
 *      <li id="emailField">
 *	    <div class="fieldZone">
 *		<input type="text" name="email" id="email" />
 *	    </div>
 *	</li>
 * - The submit button has the id technogateFormSubmit
 */
TECHNOGATE_JS.showTechnogateFormError = function(field, errormsg) {
    /** Enable the submit button */
    $j("#technogateFormSubmit").removeAttr('disabled');

    /** Yell at the user */
    $j('#'+field+'Field>.fieldZone').append('<div class="errormsg">'+errormsg+'</div>');
};
