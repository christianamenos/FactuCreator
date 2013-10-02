function cat(idelem){
  this.idelem = idelem;
  this.selectedDays = new Array(); //today
  this.year = '';
  this.month = '';
  
  //setDate need the a date in format YYYY/mm/dd that will use to set the calendar on the specified date
  this.setDate = function(date){
    
  };
  
  //especify the minimum date available to select on the calendar today by default
  this.minDate = '';
  
}
