/*============OBJECT==================*/
var SM_SliderObj = {
    _slider: null,
    _items: null,
    _totalItem: 0,
    _current: 0,
    _next: 0,

    _init: function (slider_id) {
        this._slider = document.getElementById(slider_id);
        this._items = this._slider.getElementsByClassName('sm-item');
        this._totalItem = this._items.length - 1;
        this._setActiveSlide(this._current);

        this._slider.getElementsByClassName('ctr-prev-slide')[0].addEventListener('click', this, false);
        this._slider.getElementsByClassName('ctr-next-slide')[0].addEventListener('click', this, false);
    },

    handleEvent: function (e) {
        if (e.type === 'click') {
            switch (e.target.getAttribute('class')) {
                case "ctr-prev-slide":
                    this._prevSlide();
                    break;
                case "ctr-next-slide":
                    this._nextSlide();
                    break;
            }
        }
    },

    _nextSlide: function () {
        if (this._current < this._totalItem)
            this._next = this._current + 1;
        else
            this._next = 0;

        this._setDeActiveSlide(this._current);
        this._setActiveSlide(this._next);
    },

    _prevSlide: function () {
        if (this._current > 0)
            this._next = this._current - 1;
        else
            this._next = this._totalItem;

        this._setDeActiveSlide(this._current);
        this._setActiveSlide(this._next);
    },

    _setActiveSlide: function (slide_pos) {
        this._current = slide_pos;
        this._items[slide_pos].setAttribute('class', 'sm-item sm-active');
    },

    _setDeActiveSlide: function (slide_pos) {
        this._items[slide_pos].setAttribute('class', 'sm-item');
    }
};

function sm_slider(slider_id){
    var _slider = Object.create(SM_SliderObj);
    _slider._init(slider_id);
}

//================FUNCTION===================
// var current = 0;
// var next = 0;
//
// function showSlide(slider_id, control) {
//     var slider = document.getElementById(slider_id);
//     var items = slider.getElementsByClassName('sm-item');
//     var total_items = items.length - 1;
//
//     if (control === 'next') {
//         if (current < total_items) next = current + 1;
//         else next = 0;
//     } else {
//         if (current > 0) next = current - 1;
//         else next = total_items;
//     }
//
//     items[current].setAttribute('class', 'sm-item');
//     items[next].setAttribute('class', 'sm-item sm-active');
//
//     current = next;
// }

// setInterval(showSlide('next'), 2000);