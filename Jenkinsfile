pipeline {
  agent any
  stages {
    stage('Magento Setup') {
      steps {
        echo 'magento_setup'
      }
    }
    stage('Tests') {
      steps {
        parallel(
          "integrarion": {
            echo 'integration'
            
          },
          "static": {
            echo 'static'
            
          },
          "unit": {
            echo 'unit'
            
          }
        )
      }
    }
    stage('Tool Setup') {
      steps {
        echo 'tool setup'
      }
    }
    stage('Build Assets') {
      steps {
        parallel(
          "production mode": {
            echo 'production mode'
            
          },
          "compile": {
            echo 'compile'
            
          },
          "static:content": {
            echo 'static_content'
            
          }
        )
      }
    }
    stage('Create Artifacts') {
      steps {
        echo 'create artifacts'
      }
    }
    stage('Deploy') {
      steps {
        echo 'deploy'
      }
    }
  }
}