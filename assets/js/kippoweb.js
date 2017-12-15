$(document).ready(function() {
    window.showSpoiler = function(obj) {
        $(obj.parentNode.getElementsByTagName('div')[0]).collapse('toggle')
    }
}
)
