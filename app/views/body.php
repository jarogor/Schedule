<div class="container">

    <div class="row mt-5 mb-5">
        <h1 class="col-6">Расписание</h1>
        <div class="col-6 text-right">
            <button class="btn btn-link btn-sm get-clear-data">Очистить все данные</button>
            <button class="btn btn-link btn-sm get-generate-data">Сгенерировать новое расписание</button>
        </div>
    </div>


    <div class="row mb-3">
        <form>
            <div class="form-inline">

                <select name="regions" id="regions" class="form-control mr-3">
                    <option value="0" disabled selected>- регион -</option>
                    <?php foreach ($data['regionsList'] as $region): ?>
                        <option value="<?=$region['id']?>"><?=$region['name']?></option>
                    <?php endforeach; ?>
                </select>

                <input type="date" id="date_start" name="date_start" class="form-control mr-3" placeholder="дд.мм.гггг">

                <select name="couriers" id="couriers" class="form-control mr-3">
                    <option value="0" disabled selected>- курьер -</option>
                    <?php foreach ($data['couriersList'] as $courier): ?>
                        <option value="<?=$courier['id']?>"><?=$courier['name']?></option>
                    <?php endforeach; ?>
                </select>

                <input type="date" id="date_end" name="date_end" class="form-control mr-3" disabled placeholder="дд.мм.гггг">

                <button type="button" class="btn btn-primary">Добавить</button>

            </div>
        </form>
    </div>


    <div class="row md-2">
        <div class="col alert"></div>
    </div>


    <div class="row">
        <table class="table table-striped">
            <caption>Состояние на сегодня</caption>
            <thead>
            <tr>
                <th scope="col">Курьер</th>
                <th scope="col">Регион</th>
                <th scope="col">Отправка</th>
                <th scope="col">Прибытие <br><small>(местное время)</small></th>
                <th scope="col">Возвращение</th>
                <th scope="col">Статус</th>
            </tr>
            </thead>

            <tbody>
            <?php foreach($data['scheduleTodayList'] as $task): ?>
                <tr>
                    <td><?=$task['courier']?></td>
                    <td><?=$task['region']?></td>
                    <td><?=date('d.m.Y \<\u\>\<\s\u\p\>H:i\<\/\s\u\p\>\<\/\u\>', strtotime($task['date_start']))?></td>
                    <td><?=date('d.m.Y \<\u\>\<\s\u\p\>H:i\<\/\s\u\p\>\<\/\u\>', strtotime($task['date_end']))?></td>
                    <td><?=date('d.m.Y \<\u\>\<\s\u\p\>H:i\<\/\s\u\p\>\<\/\u\>', strtotime($task['date_reverse']))?></td>
                    <td><?=($task['state'] == 1)
                            ? '<span class="badge badge-success">Возвращается</span>'
                            : '<span class="badge badge-secondary">В пути</span>'?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>


</div>
