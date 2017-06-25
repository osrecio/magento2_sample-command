pipeline {
  agent {
    node {
      label 'qw'
    }
    
  }
  stages {
    stage('Magento Setup') {
      steps {
        sh '1'
      }
    }
    stage('Tests') {
      steps {
        parallel(
          "integrarion": {
            sh 'Unit'
            
          },
          "static": {
            echo 'ie'
            
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