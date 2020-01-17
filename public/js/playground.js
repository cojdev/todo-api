const form = qs('form');
const endpoint = qs('#endpoint');
const method = qs('#method');
const parameters = qs('#parameters');
const request = qs('#request-body');

const requestEditor = new JSONEditor(qs('.request-editor'), { mode: 'code' });
const responseViewer = new JSONEditor(qs('.response-viewer'), { mode: 'view' });

const endpoints = {
  task: {
    GET: null,
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
});

method.evt('input', setExample);

form.evt('submit', (e) => {
  e.preventDefault();
  const url = `/${qs('#endpoint').value.replace(':id', qs('#id').value)}`;
  const body = requestEditor.get();
  console.log(body);

  ajax(url, qs('#method').value, body)
    .then((data) => {
      console.log(data.parsed);
      responseViewer.set(data.parsed);
    })
    .catch((data) => {
      console.log(data.parsed);
      responseViewer.set(data.parsed);
    });
});
