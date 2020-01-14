const form = qs('form');
const endpoint = qs('#endpoint');
const method = qs('#method');
const parameters = qs('#parameters');
const request = qs('#request-body');
const date = new Date();

const endpoints = {
  task: {
    GET: null,
    POST: {
      description: 'what needs to be done',
      due: `${date.getFullYear()}-${date.getMonth()+1}-${date.getDate()+1}`,
      starred: false,
    },
  },
  'task/:id': {
    GET: null,
    PATCH: {
      description: 'modified post',
      due: `${date.getFullYear()}-${date.getMonth()+1}-${date.getDate()+2}`,
      starred: true,
    },
    DELETE: null,
  },
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
  request.innerHTML = json ? JSON.stringify(json, null, 2) : null;
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
  const body = qs('#request-body').value ? JSON.parse(qs('#request-body').value) : null;
  console.log(body);

  ajax(url, qs('#method').value, body)
    .then((data) => {
      console.log(data.parsed);
      qs('#response pre').innerHTML = `${JSON.stringify(data.parsed, null, 2)}`;
    })
    .catch((data) => {
      console.log(data.parsed);
      qs('#response pre').innerHTML = `${JSON.stringify(data.parsed, null, 2)}`;
    });
});

qs('#import-button').evt('click', e => {
  e.preventDefault();
  const url = `/import`;
  const body = qs('#import-json').value ? JSON.parse(qs('#import-json').value) : null;
  console.log(body);

  ajax(url, 'POST', body)
    .then((data) => {
      console.log(data.parsed);
      qs('#response pre').innerHTML = `${JSON.stringify(data.parsed, null, 2)}`;
    })
    .catch((data) => {
      console.log(data.parsed);
      qs('#response pre').innerHTML = `${JSON.stringify(data.parsed, null, 2)}`;
    });
});
