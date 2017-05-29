/**
 * Created by 3Q GmbH
 */
function sdnPlayerBridge(playerId, event, data) {
    switch (event) {
        case "ready":

        break;
    }
}
function sdnResize() {
    document.getElementById(playerId).setAttribute('style', 'height'+document.getElementById(playerId).clientWidth/1.7778);
}
window.addEventListener('resize', sdnResize, false);