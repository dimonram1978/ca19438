BX.namespace('Homework.BeginDateButton');

BX.Homework.BeginDateButton = {
    showMessage: function (message) {
        alert(message);
    },
    onStartWorkingDateAction: function (popupNodeId) {
        var popup = BX.PopupWindowManager.create("greeting-popup-message", BX(popupNodeId), {
            content: 'Вы хотите начать рабочий день?',
            width: 600, // ширина окна
            height: 400, // высота окна
            zIndex: 100, // z-index
            offsetTop: 0,
            offsetLeft: 0,
            closeIcon: {
                // объект со стилями для иконки закрытия, при null - иконки не будет
                opacity: 1
            },
            titleBar: 'Начало рабочего дня',
            closeByEsc: true, // закрытие окна по esc
            darkMode: false, // окно будет светлым или темным
            autoHide: true, // закрытие при клике вне окна
            draggable: true, // можно двигать или нет
            resizable: true, // можно ресайзить
            min_height: 100, // минимальная высота окна
            min_width: 100, // минимальная ширина окна
            lightShadow: true, // использовать светлую тень у окна
            angle: true, // появится уголок
            overlay: {
                backgroundColor: 'black',
                opacity: 500
            },
            buttons: [
                new BX.PopupWindowButton({
                    text: 'Начать', // текст кнопки
                    id: 'save-btn', // идентификатор
                    className: 'ui-btn ui-btn-success', // доп. классы
                    events: {
                        click: function() {
                            BX.Homework.BeginDateButton.startDate();
                            //BX.Homework.BeginDateButton.ajaxload();
                            this.popupWindow.close();
                        }
                    }
                }),
                new BX.PopupWindowButton({
                    text: 'Отмена',
                    id: 'copy-btn',
                    className: 'ui-btn ui-btn-primary',
                    events: {
                        click: function() {
                            popup.close();
                        }
                    }
                })
            ],
            events: {
                /*onPopupShow: function() {
                    BX.Homework.BeginDateButton.showMessage('Вы открыли окно начала рабочего дня!');
                },
                onPopupClose: function() {
                    BX.Homework.BeginDateButton.showMessage('Вы закрыли окно начала рабочего дня!');
                },*/
                onAfterPopupShow: function()
                {
                    this.setContent(BX("bx_recall_popup_form"));
                }
            }
        });

        popup.show();
    },
    startDate: function () {
        BX.ajax.runAction('aholin:crmcustomtab.TimemanActions.TimemanController.startDate', {
        //BX.ajax.runAction('\local\js\Controllers\TimemanActions\TimemanController.startDate', {
        //BX.ajax.runAction('otus:mymodule.TimemanActions.TimemanController.startDate', {
            data: {},
        }).then(function (response) {
            //console.log(1);
	//console.log(response);
    location.reload();
	/**
		 {
			"status": "success",
			"data": "Hi Hero!",
			"errors": []
		}
	 **/
}, function (response) {
	//сюда будут приходить все ответы, у которых status !== 'success'
    console.log(2);
	console.log(response);
	/**
		{
			"status": "error",
			"errors": [...]
		}
	 **/
});
    },
    ajaxload: function () {
       //var test= document.getElementById('test').value; 
       var time_hours= document.querySelector('.time-hours').innerHTML;
       var time_minutes= document.querySelector('.time-minutes').innerHTML;
       //console.log(time_hours);
       var request = new XMLHttpRequest();
          function reqReadyStateChange() {
           if (request.readyState == 4 && request.status == 200){
               let html = document.getElementById("user-name").innerHTML; 
              // console.log(request.responseText);
              // html = html + request.responseText;
              
              $userName = request.responseText;
              var_dump(request.responseText);
              //console.log(time_minutes);
               //document.getElementById("user-name").innerHTML= $userName+' вы начали рабочий день в '+time_hours+':'+time_minutes;
            }
          }
  
       var goToUrl= '../local/ajax/TimemanController.php';
       //var body= 'text='+test;
       request.open("POST", goToUrl);
       request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
       request.onreadystatechange = reqReadyStateChange;
       request.send();
    }
    
};
 
BX.addCustomEvent('onTimeManWindowBuild', function () {

    let timemanPopup = BX('timeman_main');
    let startOrContinueDayButton = timemanPopup.querySelector('button.ui-btn.ui-btn-icon-start');
    let stopDayButton = timemanPopup.querySelector('button.ui-btn.ui-btn-icon-stop');
    
    let parent1 = timemanPopup.querySelector('.tm-popup-button-handler');
     
    let before1 = timemanPopup.querySelector('button.ui-btn.ui-btn-icon-start');
    let popupbutt = document.createElement('button');
    
    popupbutt.id = 'icon-start-new'; 
    popupbutt.classList.add('ui-btn');
    popupbutt.classList.add('ui-btn-success');
    popupbutt.classList.add('ui-btn-icon-start-new');
    popupbutt.innerHTML = 'Нажмите для начала рабочего дня';
      
    if(stopDayButton == null){ console.log(stopDayButton); parent1.insertBefore(popupbutt, before1);
        startOrContinueDayButton.remove();}
     
    popupbutt.onclick = function (event) {
    
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();

        let parent = document.getElementById('timeman-block');
        let before = document.getElementById('timeman-timer');
        let popupDiv = document.createElement('div');
        popupDiv.id = 'greeting-message-popup';
        parent.insertBefore(popupDiv, before); 
        BX.Homework.BeginDateButton.onStartWorkingDateAction(popupDiv.id);
    
        return false;
    };
    this.timemanPopup.close();
    //popupbutt.id = 'ui-btn-icon-start-new';
    //startOrpopupbuttContinueDayButton.innerHTML = 'Нажмите для начала рабочего дня';
    //parent.insertBefore(popupbutt, before);
    
    /*startOrContinueDayButton.innerHTML = 'Нажмите для начала рабочего дня';
    startOrContinueDayButton.onclick = function (event) {
    
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();

        let parent = document.getElementById('timeman-block');
        let before = document.getElementById('timeman-timer');
        let popupDiv = document.createElement('div');
        popupDiv.id = 'greeting-message-popup';
        parent.insertBefore(popupDiv, before); 
        BX.Homework.BeginDateButton.onStartWorkingDateAction(popupDiv.id);

        return false;
    };*/

});
  


    