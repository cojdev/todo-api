/* eslint-disable */
const qs = (selector, element = document) => {
  const nodeList = element.querySelectorAll(selector);

  if (nodeList.length === 1) {
    return nodeList[0];
  }

  return nodeList;
};

EventTarget.prototype.evt = function (event, callback) {
  this.addEventListener(event, callback);
};

function ajax(url, method, requestBody = null) {
  return new Promise((resolve, reject) => {
    const xhr = new XMLHttpRequest();

    xhr.onload = () => {
      if (xhr.status >= 200 && xhr.status < 400) {
        // Success!
        try {
          return resolve({
            raw: xhr.responseText,
            parsed: JSON.parse(xhr.responseText),
          });
        } catch (e) {
          if (e.message.includes('end of JSON')) {
            return resolve({ raw: xhr.responseText });
          }
          console.log(e.message);
          console.error(e);
        }
      } else {
        // We reached our target server, but it returned an error
        return reject({
          raw: xhr.responseText,
          parsed: JSON.parse(xhr.responseText),
        });
      }
    };

    xhr.open(method, url);


    console.log(method);

    if (['POST', 'PATCH'].includes(method)) {
      console.log(method);
      xhr.setRequestHeader('Content-Type', 'application/json');
    }

    xhr.send(JSON.stringify(requestBody));
  });
}
