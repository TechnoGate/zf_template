/**
 * Technogate
 *
 * @package Notification
 * @author Wael Nasreddine (TechnoGate) <wael@technogate.fr>
 */

function Notification(){
	this.$bar=$j('<div class="notification-bar"></div>');
	this.$barContainer=$j('<div class="notification-bar-container"></div>');
	this.$barContents=$j('<div class="notification-bar-contents"></div>');
	this.$barBackground=$j('<div class="notification-bar-bkg"></div>');
	this.$message=$j('<div class="message"></div>');
	this.$bar.hide();
	this.$barBackground.hide();
	this.$bar.click(function(event){
		this.removeAfterEvent(event)
	});
	this.className="message-info"
	this.timeoutInMilliseconds=3000
}

Notification.SLIDE_SPEED_IN_MS=300;
Notification.prototype.remove=function(){
	var Obj=this;
	this.slideUp(function(){
		Obj.$bar.remove();
		Obj.$barBackground.remove();
		window.clearTimeout(Obj.timeout)
	}
)};

Notification.prototype.removeAfterEvent=function(event){
	var target=$(event.target);
	if(target.get(0).nodeName.toLowerCase() == "a" && target.hasParent(this.$message)){
		return
	}
	this.remove()
};

Notification.prototype.setMessage=function(msg){
	this.msg=msg;
	return this
};

Notification.prototype.show=function(){
	this.$message.addClass(this.className).html(this.msg);
	this.$barContainer.append(this.$barBackground).append(this.$bar.append(this.$barContents.append(this.$message)));
	$j("#notifications").append(this.$barContainer);
	this.$barBackground.height(this.$bar.height());
	this.showBar();
	if(this.onShow){
	this.onShow()}
	return this
};

Notification.prototype.removeInMilliseconds=function(){
	var Obj=this
	Obj.timeout=window.setTimeout(function(){ Obj.remove()} , Obj.timeoutInMilliseconds)
};

Notification.prototype.showBar=function(){
	this.$bar.show();
	this.$barBackground.show()
	this.$bar.slideDown(Notification.SLIDE_SPEED_IN_MS);
	this.$barBackground.slideDown(Notification.SLIDE_SPEED_IN_MS)
};

Notification.prototype.onShow=function(){
	this.removeInMilliseconds()
};

Notification.prototype.slideUp=function(A){
	this.$bar.slideUp(Notification.SLIDE_SPEED_IN_MS);
	this.$barBackground.slideUp(Notification.SLIDE_SPEED_IN_MS,A)
};
