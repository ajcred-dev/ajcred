<?php

namespace app\models;

use Yii;
use \app\models\base\Usuario as BaseUsuario;
use yii\web\IdentityInterface;

/**
 * Classe de repositorio Usuario
 * 
 * @author opas
 *        
 */
class Usuario extends BaseUsuario implements IdentityInterface {
	public $senha_antiga;
	public $confirma_senha;
	
	/**
	 * Diferentes scenarios para validação.
	 * 
	 * @var string
	 */
	const SCENARIO_LOGIN = 'login';
	const SCENARIO_RECUPERA_SENHA = 'recuperasenha';
	const SCENARIO_CADASTRAR = 'cadastrar';
	const SCENARIO_ALTERAR_SENHA = 'alterarsenha';

    static $users = [];
	
		
	
	public function scenarios() {
		$scenarios = parent::scenarios ();
		$scenarios [self::SCENARIO_LOGIN] = [ 
				'email',
				'senha' 
		];
		
		$scenarios [self::SCENARIO_RECUPERA_SENHA] = [ 
				'email' 
		];
		
		$scenarios [self::SCENARIO_CADASTRAR] = [ 
				'email',
				'senha',
				'confirma_senha' 
		];
		
		$scenarios [self::SCENARIO_ALTERAR_SENHA] = [ 
				'senha',
				'senha_antiga',
				'confirma_senha' 
		];
		
		return $scenarios;
	}

	public function rules() {
		return [
				
				// SCENARIO LOGIN
				[ 
					[ 
						'email',
						'senha' 
					],
					'required',
					'on' => self::SCENARIO_LOGIN 
				],
				[ 
					[ 
						'email',
					],
					'email',
					'on' => self::SCENARIO_LOGIN 
				],
				[ 
					'email',
					'verificaEmail',
					'on' => self::SCENARIO_LOGIN 
				],
				[ 
					'senha',
					'verificaSenha',
					'on' => self::SCENARIO_LOGIN 
				],
				
				// SCENARIO CADASTRAR
				[ 
					[ 
						'email',
						'senha',
						'confirma_senha' 
					],
					'required',
					'on' => self::SCENARIO_CADASTRAR 
				],
				[ 
					[ 
							'email' 
					],
					'email',
					'on' => self::SCENARIO_CADASTRAR 
				],
				[ 
					[ 
							'confirma_senha' 
					],
					'compare',
					'compareAttribute' => 'senha',
					'on' => self::SCENARIO_CADASTRAR 
				],
				[ 
					[ 
							'email' 
					],
					'verificaEmailCadastrado',
					'on' => self::SCENARIO_CADASTRAR 
				],
				
				// SCENARIO RECUPERA SENHA
				[ 
					'email',
					'required',
					'on' => self::SCENARIO_RECUPERA_SENHA 
				],
				[ 
					'email',
					'email',
					'on' => self::SCENARIO_RECUPERA_SENHA 
				],
				[
					'email',
					'verificaEmail',
					'on' => self::SCENARIO_RECUPERA_SENHA
				],
				
				// SCENARIO ALTERAR SENHA
				[ 
					[ 
							'email',
							'senha',
							'confirma_senha' 
					],
					'required',
					'on' => self::SCENARIO_ALTERAR_SENHA 
				],
				[ 
					'senha_antiga',
					'verificaSenhaLogado',
					'on' => self::SCENARIO_ALTERAR_SENHA 
				],
				[ 
					[ 
							'senha_rep' 
					],
					'compare',
					'compareAttribute' => 'senha',
					'on' => self::SCENARIO_ALTERAR_SENHA 
				] 
		];
	}
	
	public function verificaSenhaLogado() {
		if (! $this->find ()->where ( [ 
				'senha' => $this->senha_antiga,
				'id' => \Yii::$app->user->identity->id 
		] )->count ()) {
			$this->addError ( 'senha_antiga', 'Senha Incorreta' );
			return false;
		}
	}
	public function verificaSenha() {
		// Verifico se tem erro no email
		if (! count ( $this->getErrors () )) {
			if (! $this->find ()->where ( [ 
					'senha' => $this->senha,
					'email' => $this->email 
			] )->count ()) {
				$this->addError ( 'senha', 'Senha Incorreta' );
				return false;
			}
		}
		return true;
	}
	
	public function verificaEmail() {
		if (! $this->find ()->where ( [ 
				'email' => $this->email 
		] )->count ()) {
			$this->addError ( 'email', 'E-mail Inexistente' );
			return false;
		}
		return true;
	}
	
	public function verificaEmailCadastrado() {
		if ($this->find ()->where ( [ 
				'email' => $this->email 
		] )->count ()) {
			$this->addError ( 'email', 'E-mail já cadastrado em nossa base de dados' );
			return false;
		}
		return true;
	}
	
	public function login() {
		if ($this->validate ()) {
			$user = self::findOne ( [ 
					'email' => $this->email,
					'senha' => $this->senha 
			] );
			return \Yii::$app->user->login ( $user, 0 );
		}
		return false;
	}
	
	public static function findIdentity($id) {
		return self::findOne ( $id );
	}
	
	public static function findIdentityByAccessToken($token, $type = null) {
		foreach ( self::$users as $user ) {
			if ($user ['accessToken'] === $token) {
				return new static ( $user );
			}
		}
		
		return null;
	}

	public static function findByUsername($username) {
		foreach ( self::$users as $user ) {
			if (strcasecmp ( $user ['username'], $username ) === 0) {
				return new static ( $user );
			}
		}
		
		return null;
	}

	public function getId() {
		return $this->id;
	}
	public function getAuthKey() {
		return true;
	}
	
	/**
	 * @inheritdoc
	 */
	public function validateAuthKey($authKey) {
		return true;
	}
	
	
	
	
	/**
	 * Função verifica se o usuário logado é administrador.
	 * @todo trocar para admin-rh e admin-edital
	 */
	public function isAdmin()
	{
		if($this->authAssignments[0]->item_name == 'admin' 
				|| $this->authAssignments[0]->item_name == 'admin-edital'
				|| $this->authAssignments[0]->item_name == 'admin-rh')
		{
			return true;
		}
		return false;		
	}
	
	/**
	 * Maior 
	 */
	public function isZeus()
	{
		if($this->authAssignments[0]->item_name == 'admin')
		{
			return true;
		}
		return false;
	}
    
	
	public function getPerfil()
	{
		return $this->authAssignments[0]->item_name;
	}
	
}