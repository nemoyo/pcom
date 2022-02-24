const addList = document.getElementById("add-list");
// 行番号
let lineNumber = 0;
// ポイントの合計
const totalPoints = [];

// イベント
// リスト追加ボタンclickイベントで発火する関数
addList.addEventListener("click", () => {
  addTableRow(true);
});

// 関数
// 付与ポイントIDのonChangeイベントで発火する関数
function getPointId(value, lineNumber) {
  console.log(value);
  if (value){
    getPointDetail(value);
  }
  async function getPointDetail(pointId){
    // JSON を読み込む
    let response = await fetch(`/point/${pointId}`);
    let point = await response.json();

    console.log(point);

    // タイトル
    const pointName = document.getElementById(`point-name${lineNumber}`);
    pointName.innerText = point.name;
    inputHiddenItem(`point-name${lineNumber}`, "point-name[]", point.name);

    // 摘要
    const pointComment = document.getElementById(`point-comment${lineNumber}`);
    pointComment.innerText = point.comment;
    inputHiddenItem(`point-comment${lineNumber}`, "point-comment[]", point.comment);

    // 付与ポイント
    const attachPoint = document.getElementById(`point${lineNumber}`);
    const oldAttachPoint = attachPoint.innerText;
    if (oldAttachPoint){
      totalPointAddOrRemove(oldAttachPoint, false);
    }
    attachPoint.innerText = point.point;
    totalPointAddOrRemove(point.point, true);
    inputHiddenItem(`point${lineNumber}`, "point[]", point.point);
  }
}

/**
 * 隠し項目に値をセット
 * parentElementがセットされていた場合はそれを使用する
 * @param {string} parentItemId 隠し項目を配置する親要素のID名
 * @param {string} name name属性の値
 * @param {string} value value属性の値
 * @param {object} parentElement 隠し項目を配置する親要素のElement
 */
const inputHiddenItem = (parentItemId, name, value, parentElement) => {
  let parentItem = document.getElementById(parentItemId);
  if(parentElement) parentItem = parentElement;

  if (!parentItem.querySelector("input[type='hidden']")) {
    const hiddenItem = document.createElement("input");
    hiddenItem.setAttribute("type", "hidden");
    hiddenItem.setAttribute("name", name);
    hiddenItem.setAttribute("value", value);
    parentItem.appendChild(hiddenItem);

  } else {
    parentItem.querySelector("input[type='hidden']").value = value;
  }
}

/**
 * 削除するデータの主キーをname属性に付与するためのテーブルを作成
 * tableが無かったら新規作成し、存在すれば行追加
 * @param {string} primaryKey 削除する行データの主キー
 */
const createTableDeleted = (primaryKey) => {
  if (!document.getElementById("delete_table")){
    // tableが無い場合は外側だけ作る
    const form = document.getElementsByTagName("form")[0];

    const tbl = document.createElement("table");
    const tblBody = document.createElement("tbody");
    tbl.setAttribute("id","delete_table");

    tbl.appendChild(tblBody);
    form.appendChild(tbl);
  }

  // 行を追加して主キーを設定
  const deleteTable = document.getElementById("delete_table");
  let newRow = deleteTable.insertRow(-1);
  const cell = document.createElement("td");
  inputHiddenItem('','delete_key[]', primaryKey, cell);
  newRow.appendChild(cell);
}

// 削除ボタン押下時に発火する関数
function deleteTableRow(index) {
  const deletePoint = document.getElementById(`point${index}`).innerText;
  if (deletePoint){
    totalPointAddOrRemove(deletePoint, false);
  }

  // 詳細テーブルの主キーを取得
  const primaryKey = document.getElementsByName('detail_id[]')[index-1];
  if (primaryKey) {
    createTableDeleted(primaryKey.value);
  }

  const table = document.getElementById("target-table");
  table.deleteRow(index);

  refreshLineNumber();
}

// 行を追加する関数
const addTableRow = isManualClick => {
  // ユーザーのIDを取得
  const user_id = document.getElementById("user_id");
  const userName = document.getElementById("user_name");

  let table = document.getElementById("target-table");
  let newRow = table.insertRow(-1);
  lineNumber = table.rows.length -1;
  console.log('行の数' + lineNumber);

  // 申請者ID
  let newUserId = newRow.insertCell(0);
  newUserId.innerHTML = user_id.value;

  // 申請者
  let newUserName = newRow.insertCell(1);
  newUserName.innerHTML = userName.textContent;

  // ポイントID
  let attachPointId = newRow.insertCell(2);
  attachPointId.innerHTML = `<input type="text" id="attach-point-id${lineNumber}" name="attach-point-id[]" onchange="getPointId(this.value, ${lineNumber});" size="1" maxlength="3">`;

  // 付与日
  let attachPointDate = newRow.insertCell(3);
  attachPointDate.innerHTML = `<input type="text" name="attach-point-date[]" size="8" maxlength="8">`;

  // 理由
  let newReason = newRow.insertCell(4);
  // newReason.setAttribute('name', 'point-reason[]');
  newReason.innerHTML = `<input type="text" name="point-reason[]">`
  // newReason.addEventListener("change", e => {
  //   console.log("foo");
  //   console.log(e.currentTarget);
  //   console.log(e.currentTarget.nextElementSibling);

  //   console.log(e.target);
  // });

  // タイトル
  let newPointName = newRow.insertCell(5);
  newPointName.setAttribute('id', `point-name${lineNumber}`);

  // 摘要
  let newPointComment = newRow.insertCell(6);
  newPointComment.setAttribute('id', `point-comment${lineNumber}`);

  // 付与ポイント
  let newPoint = newRow.insertCell(7);
  newPoint.setAttribute('id', `point${lineNumber}`);

  // 削除ボタン
  let newDeleteBtn = newRow.insertCell(8);
  newDeleteBtn.innerHTML = `<button type="button" onclick="deleteTableRow(${lineNumber})">削除</button>`;

  // 初期リロード時に初期値を設定
  if (!isManualClick) {
    // submit時にバリデーションエラー時にold値を設定するのをJavaScriptで実装
    // セッションストレージから付与ポイントID等のテーブル行の入力値を取得
    const sessionPointFormObj = JSON.parse(window.sessionStorage.getItem('pointFormJson'));
    let sessionPointId = "";
    let sessionPointDate = "";
    if (sessionPointFormObj) {
      sessionPointId = sessionPointFormObj[lineNumber-1].attachPointId;
      sessionPointDate = sessionPointFormObj[lineNumber-1].attachPointDate;
    }
    if (sessionPointId !== null) {
      attachPointId.firstElementChild.setAttribute("value", sessionPointId);
      getPointId(sessionPointId, lineNumber);
    }
    if (sessionPointDate !== null) {
      attachPointDate.firstElementChild.setAttribute("value", sessionPointDate);
    }
  }
};

/**
 * 合計ポイントを増減して画面に表示する
 * @param {number} point  増減するポイント
 * @param {boolean} isAdd 増やす場合はtrue, 減らす場合はfalse
 **/
const totalPointAddOrRemove = (point = 0, isAdd) => {
  let totalPoint = 0;
  if (!point) return;

  if (isAdd){
    // pointがundefinedの場合の対応
    totalPoints.push(Number(point));
    totalPoint = totalPoints.reduce((accumulator, currentValue) => {
      return accumulator + currentValue;
    }, 0);
  } else {
    if (totalPoints.indexOf(Number(point)) === -1)  return;

    totalPoints.splice(totalPoints.indexOf(Number(point)), 1);
    totalPoint = totalPoints.reduce((accumulator, currentValue) => {
      return accumulator + currentValue;
    }, 0);
  }
  document.getElementById("total-point").firstElementChild.querySelector('span').innerText = totalPoint + "pt";
  inputHiddenItem("total-point", "total-point",totalPoint);
}

/**
 * テーブルの行番号を振りなおす処理
 * deleteTableRow が呼ばれたら実行する
 */
function refreshLineNumber(){
  const table = document.getElementById("target-table");
  const index = table.rows.length -1;
  lineNumber = index;

  for (let i = 1; i <= index; i++) {
    // 付与マスタID
    const attachPointId = table.rows[i].cells[2].firstElementChild;
    attachPointId.setAttribute('id',`attach-point-id${i}`)
    attachPointId.setAttribute('onchange', `getPointId(this.value, ${i});`)

    // タイトル
    const attachPointName = table.rows[i].cells[5];
    attachPointName.setAttribute('id',`point-name${i}`)

    // 摘要
    const attachPointComment = table.rows[i].cells[6];
    attachPointComment.setAttribute('id',`point-comment${i}`)

    // 付与ポイント
    const attachPoint = table.rows[i].cells[7];
    attachPoint.setAttribute('id',`point${i}`)

    // 削除ボタン
    const deleteBtn = table.rows[i].cells[8].firstElementChild;
    deleteBtn.setAttribute('onclick', `deleteTableRow(${i})`)
  }
}
/**
 * セッションストレージに行の入力データを保存する
 * Home画面に遷移するとClearされる
 */
const setFormDataSessionStorage = () => {
  window.sessionStorage.setItem('lineNumber', lineNumber);
  // セッション変数にポイントの申請情報を登録
  const table = document.getElementById("target-table");
  const obj = [];
  console.log(table.rows[1].cells[2].firstElementChild.value);

  for (let i=1; i <= lineNumber; i++){
    console.log(table.rows[i].cells[2].firstElementChild.value);
    obj.push({
      attachPointId: table.rows[i].cells[2].firstElementChild.value,
      attachPointDate: table.rows[i].cells[3].firstElementChild.value,
    });
  }
  window.sessionStorage.setItem('pointFormJson', JSON.stringify(obj));
}
