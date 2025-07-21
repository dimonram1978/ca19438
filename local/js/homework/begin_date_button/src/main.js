BX.namespace('Homework.BeginDateButton');

BX.Homework.BeginDateButton = {
    showMessage: function (message) {
        alert(message);
    },
    onStartWorkingDateAction: function (popupNodeId) {
        var popup = BX.PopupWindowManager.create("greeting-popup-message", BX(popupNodeId), {
            content: 'Вы хотите начать рабочий день?',
            width: 400, // ширина окна
            height: 350, // высота окна
            zIndex: 100, // z-index
            offsetTop: 0,
            offsetLeft: 0,
            resizable: true, // можно ресайзить
            overlay : true,
            draggable: true, // можно двигать или нет
            closeByEsc: true, // закрытие окна по esc
            closeIcon: { right : "12px", top : "10px"},

            /*closeIcon: {
                // объект со стилями для иконки закрытия, при null - иконки не будет
                opacity: 1
            },*/
            titleBar: 'Начало рабочего дня',
            /*closeByEsc: true, // закрытие окна по esc
            darkMode: false, // окно будет светлым или темным
            autoHide: true, // закрытие при клике вне окна
            draggable: true, // можно двигать или нет
            
            min_height: 100, // минимальная высота окна
            min_width: 100, // минимальная ширина окна
            lightShadow: true, // использовать светлую тень у окна
            angle: true, // появится уголок*/
            /*overlay: {
                backgroundColor: 'black',
                opacity: 500
            },*/
            buttons: [
                new BX.PopupWindowButton({
                    text: 'Начать', // текст кнопки
                    id: 'save-btn', // идентификатор
                    className: 'ui-btn ui-btn-success', // доп. классы
                    events: {
                        click: function() {
                            //BX.Homework.BeginDateButton.startDate();
                            BX.Homework.BeginDateButton.ajaxload();
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
              document.getElementById("user-name").innerHTML= $userName+' вы начали рабочий день в '+time_hours+':'+time_minutes;
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

    startOrContinueDayButton.innerHTML = '';
    startOrContinueDayButton.onclick = function (event) {
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();

        let popupDiv = document.createElement('div');
        popupDiv.id = 'greeting-message-popup';
        document.body.appendChild(popupDiv);
        BX.Homework.BeginDateButton.onStartWorkingDateAction(popupDiv.id);

        return false;
    };
});
