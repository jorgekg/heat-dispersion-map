import subprocess

print('start environment')

print('install opencv')
subprocess.call('pip install opencv-python', shell=True)

print('install request')
subprocess.call('pip install requests', shell=True)

print('install opencv-contrib')
subprocess.call('pip install opencv-contrib-python', shell=True)

print('install pillow')
subprocess.call('pip install Pillow==2.1.0', shell=True)

print('install mysql')
subprocess.call('pip install PyMySQL', shell=True)

print('install ts-node')
subprocess.call('npm i -g ts-node', shell=True)

print('install node dependece')
subprocess.call('cd image-gender-age-detect && npm i', shell=True)
subprocess.call('cd image-expression && npm i', shell=True)
