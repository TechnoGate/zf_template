/**
 * Technogate
 *
 * @package base
 * @author Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */

function notify(enableNotification, message) {
	if(enableNotification == true) {
		sn = new Notification();
		sn.setMessage(message);
		sn.show();
	}
}
