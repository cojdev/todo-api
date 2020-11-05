/* eslint-disable */
const form = qs('form');
const endpoint = qs('#endpoint');
const method = qs('#method');
const parameters = qs('#parameters');
const request = qs('#request-body');

const requestEditor = new JSONEditor(qs('.request-editor'), { mode: 'code' });
const responseViewer = new JSONEditor(qs('.response-viewer'), { mode: 'view' });

const endpoints = {
  task: {
    GET: {},
    POST: {
      description: 'what needs to be done',
      due: `${dateFns.format(new Date(Date.now() + (1000 * 3600 * 24)), 'YYYY-MM-DD')}`,
      starred: false,
    },
  },
  'task/:id': {
    GET: {},
    PATCH: {
      description: 'modified post',
      due: `${dateFns.format(new Date(Date.now() + (1000 * 3600 * 24)), 'YYYY-MM-DD')}`,
      starred: true,
    },
    DELETE: {},
  },
  'task/import': {
    POST: IMPORT_DATA,
  }
};

const updateMethod = () => {
  method.innerHTML = Object.keys(endpoints[endpoint.value]).map(item => `
    <option value="${item}">${item}</option>
    `).join('');
};

const randomWord = (length) => {
  let ret = '';

  for (let i = 0; i < length; i++) {
    ret += String.fromCharCode(Math.floor(Math.random()*26)+97);
  }

  return ret;
};

const argTemplate = (arg) => {
  return `
    <div class="column w-4">
      <label class="label" for="arg-${arg}">${arg}</label>
      <input class="input arg-${arg}" type="text" name="arg-${arg}" value="${randomWord(5)}">
    </div>
  `;
};

const getArgs = () => endpoint.value.match(/:\w+/g);

const setArgsHTML = () => {
  const matches = getArgs();
  console.log(matches);

  let ret = '';
  
  if (matches) {
    const args = matches.map(item => item.substr(1));
    ret = args.map(arg => argTemplate(arg)).join('');
  }

  qs('.js-args').innerHTML = ret;
};

// const updateParams = () => {
//   parameters.innerHTML = Object.keys(endpoints[endpoint.value]).map(item => `
//       <option value="${item}">${item}</option>
//       `).join('');
//   // console.trace('update method');
// };

endpoint.innerHTML = Object.keys(endpoints).map(item => `
    <option value="${item}">${item}</option>
    `).join('');


updateMethod();

const setExample = () => {
  // console.log(examples[endpoint.value]);
  const json = endpoints[endpoint.value][method.value] || null; 
  // request.innerHTML = json ? JSON.stringify(json, null, 2) : null;
  requestEditor.set(json);
};

endpoint.evt('input', (e) => {
  console.log(e.currentTarget);
  updateMethod();
  setExample();
  setArgsHTML();
});

method.evt('input', setExample);

form.evt('submit', (e) => {
  e.preventDefault();

  const replacer = (a, b) => {
    const ret = qs(`.arg-${b}`).value;
    // debugger;
    return ret;
  }

  const url = `/${qs('#endpoint').value.replace(/:(\w+)/g, replacer)}`;
  console.log({url});
  const m = qs('#method').value;
  qs('#request').innerHTML = `${m} ${url}`;
  const body = requestEditor.get();
  console.log(body);

  ajax(url, m, body)
    .then((data) => {
      console.log(data.parsed);
      responseViewer.set(data.parsed);
    })
    .catch((data) => {
      console.log(data.parsed);
      responseViewer.set(data.parsed);
    });
});
