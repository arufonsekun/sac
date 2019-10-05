<?php
use App\Models\Event;
use App\Helpers\UtilsHelper;
?>

<div class="jumbotron">
    <div class="container">
        <h1>Eventos</h1>
        <p>Adicionar eventos.</p>
    </div>
</div>

<div class="container">
    <div>
        <a href="<?= UtilsHelper::base_url("/admin/evento") ?>" class="btn btn-default">Voltar</a>
    </div>
    <br>

    <div class="row">
    <form class="col-md-8" method="post">
        <div class="form-group">
            <label class="control-label">Título</label>
            <input required type="text" name="title" class="form-control" />
        </div>
                
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Dia</label>
                    <input required type="number" name="day" class="form-control">
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Mês</label>
                    <input required type="number" name="month" class="form-control">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Horário</label>
                    <input required type="text" name="time" class="form-control">
                </div>
            </div>
        </div>
            
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="control-label">Local</label>
                    <input required type="text" name="place" class="form-control">
                </div>
            </div>

            <div class="col-md-3">
                <label class="control-label">Fantasma</label>
                <select required name="ghost" class="form-control">
                    <option value="0">Não</option>
                    <option value="1">Sim</option>
                </select>
            </div>
            
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label">Custo</label>
                    <input required type="text" name="price" class="form-control">
                </div>
            </div>
        </div>
        
        <div class="row">
        </div>
            
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Vagas</label>
                    <input required type="number" name="capacity" class="form-control">
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="form-group">
                    <label class="control-label">Vagas (espera)</label>
                    <input required type="number" name="waiting_capacity" class="form-control">
                </div>
            </div>

            <div class="col-md-4">
                <label class="control-label">Competição?</label>
                <select name="fk_competition" class="form-control">
                    <option value=""></option>
                    <?php foreach($competitions as $id => $competition): ?>
                        <option value="<?= $id ?>"> <?= $competition->title ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label class="control-label">Descrição</label>
            <textarea required name="description" class="form-control" style="height: 300px;"></textarea>
        </div>
        
        <div class="row" style="margin-top: 15px;">
            <div class="col-md-7">
                <button type="submit" class="btn btn-success">Salvar</button>
            </div>
        </div>
    </form>
    </div>
    <script text="text/javascript">
        SAC.addMasks();
    </script>

</div>