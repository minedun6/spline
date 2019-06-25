Vue.directive('validate', function (el) {
  $(el).focusout(function() {
  	if($(this).val().length === 0){
  		$(this).parents('.form-group').addClass('has-error');
  	}else{
  		$(this).parents('.form-group').removeClass('has-error');
  	}
  });
})

Vue.directive('datepicker', {
  bind: function (el, binding) {
    var vm = this.vm;
    var key = this.expression;
    $(el).datepicker({
        'format' :'dd-mm-yyyy'
    });
    $(el).datepicker('setDate', binding.value);
  },
  update: function (val) {
    $(this.el).datepicker('setDate', val);
  }
});