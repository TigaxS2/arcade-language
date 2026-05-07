<?php
require_once 'includes/funcoes.php';
require_once 'includes/conexao.php';
require_once 'includes/ai_engine.php';
verificarLogado();

$nivelUsuario = $_SESSION['patente'] ?? 'Recruta';
$questoes = solicitarMissoesIA($nivelUsuario, "Geral");

if (empty($questoes)) {
    echo "<div style='color:white; background:red; padding:20px; margin:20px;'>ERRO: A IA não retornou perguntas válidas.</div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Laboratório de Idiomas - Arcadius</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .lab-container { max-width: 800px; margin: 50px auto; padding: 20px; }
        .missao-card { 
            background: rgba(0, 20, 40, 0.8);
            border: 2px solid var(--neon-cyan);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 0 20px rgba(0, 240, 255, 0.2);
        }
        .opcao-btn {
            display: block; width: 100%; padding: 18px; margin: 12px 0;
            background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(0, 240, 255, 0.3);
            color: white; cursor: pointer; transition: 0.3s; text-align: left;
            font-family: 'Orbitron', sans-serif; font-size: 1rem;
        }
        .opcao-btn:hover { background: rgba(0, 240, 255, 0.1); border-color: var(--neon-cyan); }
        .feedback { display: none; margin-top: 25px; padding: 20px; border-radius: 8px; background: rgba(0,0,0,0.4); border-left: 4px solid var(--neon-cyan); }
        .btn-audio { margin-left: 10px; cursor: pointer; background: none; border: 1px solid var(--neon-cyan); color: var(--neon-cyan); border-radius: 50%; width: 30px; height: 30px; }
    </style>
</head>
<body class="area-fundo">
    <?php include 'includes/navbar.php'; ?>

    <div class="lab-container">
        <header style="text-align: center; margin-bottom: 40px;">
            <h1 class="glow-text">LABORATÓRIO DE IDIOMAS</h1>
            <p style="color: var(--neon-cyan);">Nível: <?php echo htmlspecialchars($nivelUsuario); ?> | Missão Dinâmica</p>
        </header>

        <div id="game-area">
            <?php foreach($questoes as $index => $q): ?>
                <div class="missao-card questao" id="q-<?php echo $index; ?>" style="<?php echo $index > 0 ? 'display:none;' : ''; ?>">
                    <h3 style="margin-bottom: 25px; line-height: 1.4;">
                        <?php echo htmlspecialchars($q['pergunta']); ?>
                        <button onclick="ouvirTexto(<?php echo $index; ?>)" class="btn-audio" title="Ouvir Pergunta">🔊</button>
                    </h3>
                    
                    <div class="opcoes-container">
                        <?php foreach($q['opcoes'] as $i => $opcao): ?>
                            <button class="opcao-btn" data-qidx="<?php echo $index; ?>" data-optidx="<?php echo $i; ?>" onclick="verificarResposta(this)">
                                <?php echo htmlspecialchars($opcao); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <div id="feedback-<?php echo $index; ?>" class="feedback">
                        <div id="msg-<?php echo $index; ?>" style="margin-bottom: 10px; font-size: 1.2rem; font-weight: bold;"></div>
                        <p style="color: rgba(255,255,255,0.7); font-size: 0.9rem;"><?php echo htmlspecialchars($q['explicacao']); ?></p>
                        <br>
                        <button class="btn" onclick="proximaQuestao(<?php echo $index; ?>)">Próxima Fase >></button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        // Dados das questões injetados de forma segura
        const questData = <?php echo json_encode($questoes); ?>;

        async function verificarResposta(btn) {
            const qIdx = parseInt(btn.getAttribute('data-qidx'));
            const optIdx = parseInt(btn.getAttribute('data-optidx'));
            const questao = questData[qIdx];
            
            const fb = document.getElementById('feedback-' + qIdx);
            const msg = document.getElementById('msg-' + qIdx);
            const botoes = document.querySelectorAll(`#q-${qIdx} .opcao-btn`);

            // Desativa botões
            botoes.forEach(b => {
                b.style.pointerEvents = 'none';
                b.style.opacity = '0.5';
            });

            fb.style.display = 'block';

            if (optIdx === questao.correta) {
                msg.innerHTML = "<span style='color: #00ff00;'>CORRETO! PROCESSANDO XP...</span>";
                btn.style.background = "rgba(0, 255, 0, 0.2)";
                btn.style.border = "2px solid #00ff00";
                btn.style.opacity = "1";

                // Chamada para atualizar XP no Banco de Dados
                try {
                    const response = await fetch('auth/processa_xp_lab.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ token_seguranca: 'ARCADIUS_LAB_XP_2026' })
                    });
                    
                    if (!response.ok) {
                        throw new Error("HTTP Error: " + response.status);
                    }

                    const data = await response.json();
                    
                    if (data.success) {
                        msg.innerHTML = `<span style='color: #00ff00;'>CORRETO! +10 XP (Total: ${data.novo_xp})</span>`;
                        const patenteElem = document.querySelector('header p');
                        if (patenteElem) {
                            patenteElem.innerHTML = `Nível: ${data.patente} | Missão Dinâmica`;
                        }
                    } else {
                        msg.innerHTML = "<span style='color: #ffd700;'>SISTEMA: Erro no XP (" + data.message + ")</span>";
                        alert("Aviso do Sistema: " + data.message);
                    }
                } catch (e) {
                    console.error("Erro ao processar XP:", e);
                    msg.innerHTML = "<span style='color: #ff0044;'>ERRO DE CONEXÃO AO SERVIDOR</span>";
                    alert("Erro de Conexão: O servidor não respondeu adequadamente.");
                }
            } else {
                msg.innerHTML = "<span style='color: #ff0044;'>SISTEMA: RESPOSTA INCORRETA</span>";
                btn.style.background = "rgba(255, 0, 68, 0.2)";
                btn.style.border = "2px solid #ff0044";
                btn.style.opacity = "1";
                
                // Mostra a correta também
                botoes[questao.correta].style.border = "2px solid #00ff00";
                botoes[questao.correta].style.opacity = "1";

                // Lógica do Dicionário para erros
                const match = questao.pergunta.match(/['"](.*?)['"]/);
                if (match && match[1]) {
                    const palavra = match[1];
                    const dictDiv = document.createElement('div');
                    dictDiv.className = 'dict-box';
                    dictDiv.innerHTML = `<strong>Análise de Erro:</strong> Buscando dados de '${palavra}'...`;
                    msg.appendChild(dictDiv);
                
                    try {
                        const response = await fetch('get_definition.php?word=' + encodeURIComponent(palavra));
                        const definicao = await response.text();
                        dictDiv.innerHTML = `<strong>Dicionário (${palavra}):</strong><br><em>${definicao}</em>`;
                    } catch (e) {
                        dictDiv.innerHTML = "Erro ao carregar dicionário.";
                    }
                }
            }
        }

        function proximaQuestao(qIdx) {
            document.getElementById('q-' + qIdx).style.display = 'none';
            const proximo = document.getElementById('q-' + (qIdx + 1));
            if (proximo) {
                proximo.style.display = 'block';
            } else {
                alert("Missão Concluída! Retornando ao Terminal.");
                window.location.href = 'dashboard.php';
            }
        }

        function ouvirTexto(qIdx) {
            const texto = questData[qIdx].pergunta;
            window.speechSynthesis.cancel();
            const partes = texto.split(/(['"].*?['"])/g);
            let sequence = Promise.resolve();

            partes.forEach(trecho => {
                if (!trecho.trim()) return;
                sequence = sequence.then(() => {
                    return new Promise(resolve => {
                        const msg = new SpeechSynthesisUtterance();
                        msg.text = trecho.replace(/['"]/g, '');
                        const ehIngles = /^['"].*?['"]$/.test(trecho) || /\b(school|apple|go|the|is|what|how|do|you)\b/i.test(trecho);
                        msg.lang = ehIngles ? 'en-US' : 'pt-BR';
                        msg.onend = resolve;
                        window.speechSynthesis.speak(msg);
                    });
                });
            });
        }
    </script>
</body>
</html>